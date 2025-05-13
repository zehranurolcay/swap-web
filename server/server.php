<?php
// server.php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

class Chat implements MessageComponentInterface {
    protected $clients;
    private $db;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        
        // Veritabanı bağlantısı
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=swap', 'root', '');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Veritabanı bağlantısı başarılı!\n";
        } catch (PDOException $e) {
            echo "Veritabanı bağlantı hatası: " . $e->getMessage() . "\n";
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Yeni bir istemci bağlandı! (ID: {$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Bir istemci bağlantıyı kapattı! (ID: {$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $messageData = json_decode($msg, true);
        $offer_id = $messageData['offer_id']; // Gelen mesajdan offer_id al
        $sender_id = $messageData['sender_id'];
        $receiver_id = $messageData['receiver_id'];
        $message = $messageData['message'];
    
        // 1️⃣ Mesajı `messages` tablosuna ekle
        $stmt = $this->db->prepare("INSERT INTO messages (offer_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$offer_id, $sender_id, $receiver_id, $message]);
    
        echo "Mesaj alındı! Taka ID: {$offer_id}, Gönderen ID: {$sender_id}, Alıcı ID: {$receiver_id}, İçerik: {$message}\n";

        // 2️⃣ Mesajı tüm istemcilere gönder
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send(json_encode([
                    'offer_id' => $offer_id,
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'message' => $message
                ]));
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Bir hata oluştu: " . $e->getMessage() . "\n";
        $conn->close();
    }
}

// Sunucuyu başlat
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "WebSocket sunucusu 8080 portunda çalışıyor...\n";

$server->run();
