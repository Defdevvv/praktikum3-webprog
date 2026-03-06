<?php
class Api extends Controller {
    
    public function __construct() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');
    }
    
    // GET all mahasiswa - api/mahasiswa
    public function mahasiswa() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method == 'GET') {
            $data = $this->model('Mahasiswa_model')->getAllMahasiswa();
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } elseif ($method == 'POST') {
            // Create new mahasiswa
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['nama']) || empty($input['nrp']) || empty($input['email']) || empty($input['jurusan'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap'
                ]);
                return;
            }
            
            $result = $this->model('Mahasiswa_model')->tambahDataMahasiswa($input);
            
            if ($result > 0) {
                http_response_code(201);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data mahasiswa berhasil ditambahkan'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan data mahasiswa'
                ]);
            }
        }
    }
    
    // GET mahasiswa by id - api/mahasiswa/detail/1
    public function detail($id = null) {
        if ($id === null) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak ditemukan'
            ]);
            return;
        }
        
        $data = $this->model('Mahasiswa_model')->getMahasiswaById($id);
        
        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan'
            ]);
        }
    }
    
    // PUT update mahasiswa - api/mahasiswa/update
    public function update() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method == 'PUT' || $method == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['id']) || empty($input['nama']) || empty($input['nrp']) || empty($input['email']) || empty($input['jurusan'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap'
                ]);
                return;
            }
            
            // Cek apakah data ada
            $existing = $this->model('Mahasiswa_model')->getMahasiswaById($input['id']);
            if (!$existing) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data mahasiswa dengan ID ' . $input['id'] . ' tidak ditemukan'
                ]);
                return;
            }
            
            $result = $this->model('Mahasiswa_model')->ubahDataMahasiswa($input);
            
            if ($result >= 0) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data mahasiswa berhasil diubah'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal mengubah data mahasiswa'
                ]);
            }
        }
    }
    
    // DELETE mahasiswa - api/mahasiswa/delete/1
    public function delete($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method == 'DELETE' || $method == 'POST') {
            if ($id === null) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID tidak ditemukan'
                ]);
                return;
            }
            
            $result = $this->model('Mahasiswa_model')->hapusDataMahasiswa($id);
            
            if ($result > 0) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data mahasiswa berhasil dihapus'
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Data mahasiswa tidak ditemukan atau gagal dihapus'
                ]);
            }
        }
    }
}
