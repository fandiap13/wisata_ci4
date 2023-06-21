<?php

namespace App\Controllers;

use App\Models\UserModel;
use Exception;

class Login extends BaseController
{
  // private $googleClient = NULL;
  protected $UserModel;


  public function __construct()
  {
    session();
    // require_once APPPATH . "libraries/vendor/autoload.php";
    // $this->googleClient = new \Google_Client();
    // $this->googleClient->setClientId("422074532634-lburpjg5usi22se2hdur0875nspftmpr.apps.googleusercontent.com");
    // $this->googleClient->setClientSecret("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");
    // $this->googleClient->setRedirectUri("http://localhost:8080/loginWithGoogle");
    // $this->googleClient->addScope("email");
    // $this->googleClient->addScope("profile");

    $this->UserModel = new UserModel();
  }

  public function index()
  {
    return view('v_login', [
      'title' => 'Login',
      // 'googleButton' => $this->googleClient->createAuthUrl(),
      'validation' => \Config\Services::validation()
    ]);
  }

  public function register()
  {
    return view('v_register', [
      'title' => 'Register',
      'validation' => \Config\Services::validation()
    ]);
  }

  public function lupa_password()
  {
    return view('v_lupa_password', [
      'title' => 'Lupa password'
    ]);
  }

  public function cek_login()
  {
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $valid = $this->validate([
      'email' => [
        'label' => 'Email',
        'rules' => 'required|valid_email',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
          'valid_email' => '{field} tidak valid'
        ]
      ],
      'password' => [
        'label' => 'Password',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
        ]
      ],
    ]);

    if (!$valid) {
      return redirect()->to(site_url('/login'))->withInput();
    }

    $cekData = $this->UserModel->cekEmail($email);
    if ($cekData) {
      $password_verify = password_verify($password, $cekData['password_user']);
      if (!$password_verify) {
        session()->setFlashdata('msg', 'error#Password salah');
        return redirect()->to(site_url('/login'))->withInput();
      }

      $currentDateTime = date("Y-m-d H:i:s");
      $userdata = [
        'id' => $cekData['id'],
        'name' => $cekData['nama_user'],
        'email' => $cekData['email_user'],
        'profile_img' => "",
        'updated_at' => $currentDateTime,
        'role' => $cekData['role'],
      ];
      session()->set("LoggedUserData", $userdata);
      session()->setFlashData("msg", 'success#Selamat datang, ' .  $email);
      if ($cekData['role'] == "Pengunjung") {
        return redirect()->to(site_url('/'));
      } elseif ($cekData['role'] == "Admin") {
        return redirect()->to(site_url('/admin/dashboard/index'));
      }
    } else {
      session()->setFlashdata('msg', 'error#Email anda belum terdaftar');
      return redirect()->to(site_url('/login'))->withInput();
    }
  }

  public function loginWithGoogle()
  {
    // Nangkep Kode di URL
    $code = isset($_GET['code']) ? $_GET['code'] : NULL;
    if ($code == '') {
      return redirect()->to(site_url('login'));
    } else {
      $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

      $this->googleClient->setAccessToken($token['access_token']);
      session()->set('AccessToken', $token['access_token']);

      $googleService = new \Google_Service_Oauth2($this->googleClient);
      $data = $googleService->userinfo->get();

      $email = $data['email'];

      $cek_email = $this->UserModel->cekEmail($email);
      if (!$cek_email) {
        session()->setFlashdata('msg', 'error#Email anda belum terdaftar');
        return redirect()->to(site_url('/login'));
      }

      $currentDateTime = date("Y-m-d H:i:s");
      $userdata = [
        'id' => $cek_email['id'],
        'name' => $cek_email['nama_user'],
        'email' => $cek_email['email_user'],
        'profile_img' => $data['picture'],
        'updated_at' => $currentDateTime,
        'role' => $cek_email['role'],
      ];
      session()->set("LoggedUserData", $userdata);
      session()->setFlashData("msg", 'success#Selamat datang, ' .  $email);
      if ($cek_email['role'] == "Pengunjung") {
        return redirect()->to(site_url('/'));
      } elseif ($cek_email['role'] == "Admin") {
        return redirect()->to(site_url('/admin/dashboard/index'));
      }
    }
  }

  public function save_register()
  {
    $nama = $this->request->getPost('nama');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $valid = $this->validate([
      'nama' => [
        'label' => 'Nama lengkap',
        'rules' => 'required|max_length[150]',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
          'max_length' => '{field} max 150 karakter'
        ]
      ],
      'email' => [
        'label' => 'Email',
        'rules' => 'required|max_length[150]|valid_email|is_unique[users.email_user]',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
          'max_length' => '{field} max 150 karakter',
          'valid_email' => '{field} tidak valid',
          'is_unique' => '{field} ' . $email . ' sudah digunakan',
        ]
      ],
      'password' => [
        'label' => 'Password',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
        ]
      ],
      'retype_password' => [
        'label' => 'Retype password',
        'rules' => 'required|matches[password]',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
          'matches' => '{field} tidak valid'
        ]
      ],
    ]);

    if (!$valid) {
      return redirect()->to(site_url('/register'))->withInput();
    }

    $db = \Config\Database::connect();
    $setting = $db->table('setting')->select('id, nama_web')->orderBy('id', 'ASC')->get()->getRowArray();

    $title = "Halaman Konfirmasi Pendaftaran";
    $token = sha1(rand(0, 1000));
    $messange = "Akun yang kamu miliki dengan E-mail $email telah siap digunakan, silahkan melakukan aktivasi E-mail dengan cara klik link ini: " . site_url('/verifikasi/' . $email . '/' . $token);

    $sendEmail = \Config\Services::email();
    $sendEmail->setTo($email);
    $sendEmail->setFrom('crewpucksapi@gmail.com', $setting['nama_web']);
    $sendEmail->setSubject($title);
    $sendEmail->setMessage($messange);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    try {
      $this->UserModel->insert([
        'nama_user' => $nama,
        'email_user' => $email,
        'password_user' => $password_hash,
        'token_register' => $token,
        'role' => 'Pengunjung'
      ]);
      $sendEmail->send();
      session()->setFlashdata('pesan', '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Link registrasi berhasil dikirimkan ke email ' . $email . '
                </div>');
      return redirect()->to(site_url('/register'));
    } catch (\Throwable $th) {
      session()->setFlashdata('msg', 'error#Registrasi gagal !');
      return redirect()->to(site_url('/login'));
    }
  }

  public function verifikasi($email, $token)
  {
    $cekData = $this->UserModel->where('email_user', $email)->where('deleted_at IS NULL')->where('token_register', $token)->first();
    if (!$cekData) {
      session()->setFlashdata('msg', 'error#Link tidak valid. Email dan token tidak sesuai !');
      return redirect()->to(site_url('/login'));
    }

    try {
      $this->UserModel->update($cekData['id'], [
        'token_register' => NULL
      ]);
      session()->setFlashdata('msg', 'success#Akun anda berhasil diaktifkan');
      return redirect()->to(site_url('/login'));
    } catch (\Throwable $th) {
      session()->setFlashdata('msg', 'error#Akun anda gagal diaktifkan !');
      return redirect()->to(site_url('/login'));
    }
  }

  public function recover_passsword()
  {
    $email = $this->request->getPost('email');
    $cekEmail = $this->UserModel->cekEmail($email);
    if (!$cekEmail) {
      session()->setFlashdata('pesan', '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                 Akun dengan E-mail ' . $email . ' tidak terdaftar pada sistem.
                </div>');
      return redirect()->to(site_url('/lupa-password'))->withInput();
    }

    $db = \Config\Database::connect();
    $setting = $db->table('setting')->select('id, nama_web')->orderBy('id', 'ASC')->get()->getRowArray();

    $title = "Ganti Password";
    $token = sha1(rand(0, 1000));
    $messange = "Seseorang meminta untuk melakukan perubahan password pada akun anda, silahkan klik link ini : " . site_url('/ubah-password/' . $email . '/' . $token);

    $sendEmail = \Config\Services::email();
    $sendEmail->setTo($email);
    $sendEmail->setFrom('crewpucksapi@gmail.com', $setting['nama_web']);
    $sendEmail->setSubject($title);
    $sendEmail->setMessage($messange);
    try {
      $this->UserModel->update($cekEmail['id'], [
        'token_ganti_pass' => $token
      ]);
      $sendEmail->send();
      session()->setFlashdata('pesan', '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Link reset password berhasil dikirimkan ke email ' . $email . '
                </div>');
      return redirect()->to(site_url('/lupa-password'));
    } catch (\Throwable $th) {
      session()->setFlashdata('pesan', '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                  ' . $th . '
                  </div>');
      return redirect()->to(site_url('/lupa-password'));
    }
  }

  public function ubah_password($email, $token)
  {
    $cekData = $this->UserModel->where('email_user', $email)->where('deleted_at IS NULL')->where('token_register IS NULL')->where('token_ganti_pass', $token)->first();
    if (!$cekData) {
      session()->setFlashdata('msg', 'error#Link tidak valid. Email dan token tidak sesuai !');
      return redirect()->to(site_url('/login'));
    } else {
      return view('v_ubah_password', [
        'title' => 'Ubah Password',
        'email' => $email,
        'token' => $token,
        'validation' => \Config\Services::validation()
      ]);
    }
  }

  public function simpan_ubah_password()
  {
    $password = $this->request->getPost('password');
    $email = $this->request->getPost('email_user');
    $token = $this->request->getPost('token_ganti_pass');
    $cekData = $this->UserModel->where('email_user', $email)->where('deleted_at IS NULL')->where('token_register IS NULL')->where('token_ganti_pass', $token)->first();
    if (!$cekData) {
      session()->setFlashdata('msg', 'error#Link tidak valid. Email dan token tidak sesuai !');
      return redirect()->to(site_url('/login'));
    }

    $valid = $this->validate([
      'password' => [
        'label' => 'Password',
        'rules' => 'required',
        'errors' => [
          'required' => '{field} tidak boleh kosong'
        ]
      ],
      'retype_password' => [
        'label' => 'Retype password',
        'rules' => 'required|matches[password]',
        'errors' => [
          'required' => '{field} tidak boleh kosong',
          'matches' => '{field} tidak valid',
        ]
      ],
    ]);

    if ($valid) {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      try {
        $this->UserModel->update($cekData['id'], [
          'password_user' => $password_hash,
          'token_ganti_pass' => NULL,
        ]);
        session()->setFlashdata('msg', 'success#Password berhasil diubah silakan login');
        return redirect()->to(site_url('/login'));
      } catch (Exception $e) {
        session()->setFlashdata('msg', 'error#Password tidak dapat diubah !');
        return redirect()->to(site_url('/ubah-password/' . $email . '/' . $token));
      }
    } else {
      return redirect()->to(site_url('/ubah-password/' . $email . '/' . $token))->withInput();
    }
  }

  public function keluar()
  {
    session()->remove('LoggedUserData');
    session()->remove('AccessToken');

    session()->setFlashData("msg", 'error#Anda Berhasil Keluar');
    return redirect()->to(site_url('/login'));
  }
}
