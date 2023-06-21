<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Setting extends BaseController
{
    protected $SettingModel;
    public function __construct()
    {
        $this->SettingModel = new SettingModel();
    }

    public function index()
    {
        $setting = $this->SettingModel->limit(1)->orderBy('id', 'ASC')->get()->getRowArray();
        return view('admin/setting/v_setting', [
            'title' => 'Setting Web',
            'data' => $setting
        ]);
    }

    public function simpan_setting()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nama_web = $this->request->getPost('nama_web');
            $motto = $this->request->getPost('motto');
            $deskripsi_web = $this->request->getPost('deskripsi_web');
            $about_us = $this->request->getPost('about_us');
            $rekening = $this->request->getPost('rekening');
            $gambarLama = $this->request->getPost('gambarLama');
            $gambar = $this->request->getFile('gambar');
            $faviconLama = $this->request->getPost('faviconLama');
            $pembelian_tiket = $this->request->getPost('pembelian_tiket');
            $favicon = $this->request->getFile('favicon');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'gambar' => [
                    'label' => "Gambar",
                    'rules' => 'max_size[gambar,3048]|is_image[gambar]|mime_in[gambar,image/png,image/jpg,image/jpeg]',
                    'errors' => [
                        'max_size' => '{field} tidak boleh lebih dari 3 mb',
                        'is_image' => 'Yg anda input bukan gambar',
                        'mime_in' => '{field} harus jpg/jpeg/png',
                    ]
                ],
                'favicon' => [
                    'label' => "Logo",
                    'rules' => 'max_size[favicon,2048]|is_image[favicon]|mime_in[favicon,image/png,image/jpg,image/jpeg]',
                    'errors' => [
                        'max_size' => '{field} tidak boleh lebih dari 2 mb',
                        'is_image' => 'Yg anda input bukan gambar',
                        'mime_in' => '{field} harus jpg/jpeg/png',
                    ]
                ],
            ]);

            if ($valid) {
                try {
                    $namaGambar = $gambarLama;
                    if ($gambar->getError() != 4) {
                        unlink($gambarLama);
                        $nmGB = $gambar->getRandomName();
                        $gambar->move('gambar/', $nmGB);
                        $namaGambar = "gambar/" . $nmGB;
                    }

                    $namaFavicon = $faviconLama;
                    if ($favicon->getError() != 4) {
                        unlink($faviconLama);
                        $nmGB = $favicon->getRandomName();
                        $favicon->move('gambar/', $nmGB);
                        $namaFavicon = "gambar/" . $nmGB;
                    }

                    $this->SettingModel->update($id, [
                        'nama_web' => $nama_web,
                        'about_us' => $about_us,
                        'motto' => $motto,
                        'deskripsi_web' => $deskripsi_web,
                        'rekening' => $rekening,
                        'gambar' => $namaGambar,
                        'favicon' => $namaFavicon,
                        'pembelian_tiket' => $pembelian_tiket,
                    ]);

                    $json = [
                        'success' => "Data setting berhasil diubah"
                    ];
                } catch (\Throwable $th) {

                    $json = [
                        'error' => "Terdapat kesalahan pada sistem"
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'gambar' => $validation->getError('gambar'),
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function simpan_carousel()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $gambar_carousel = $this->request->getFile('gambar_carousel');
            $gambar_carouselLama = $this->request->getPost('gambar_carouselLama');
            $caption_carousel_1 = $this->request->getPost('caption_carousel_1');
            $caption_carousel_2 = $this->request->getPost('caption_carousel_2');
            $cinematic_link = $this->request->getPost('cinematic_link');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'gambar_carousel' => [
                    'label' => "Gambar Carousel",
                    'rules' => 'max_size[gambar_carousel,3048]|is_image[gambar_carousel]|mime_in[gambar_carousel,image/png,image/jpg,image/jpeg]',
                    'errors' => [
                        'max_size' => '{field} tidak boleh lebih dari 3 mb',
                        'is_image' => 'Yg anda input bukan gambar',
                        'mime_in' => '{field} harus jpg/jpeg/png',
                    ]
                ],
            ]);

            if ($valid) {
                try {
                    $namaGambar = $gambar_carouselLama;
                    if ($gambar_carousel->getError() != 4) {
                        unlink($gambar_carouselLama);
                        $nmGB = $gambar_carousel->getRandomName();
                        $gambar_carousel->move('gambar/', $nmGB);
                        $namaGambar = "gambar/" . $nmGB;
                    }

                    $this->SettingModel->update($id, [
                        'caption_carousel_1' => $caption_carousel_1,
                        'caption_carousel_2' => $caption_carousel_2,
                        'cinematic_link' => $cinematic_link,
                        'gambar_carousel' => $namaGambar
                    ]);

                    $json = [
                        'success' => "Data carousel berhasil diubah"
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => "Terdapat kesalahan pada sistem"
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'gambar_carousel' => $validation->getError('gambar_carousel'),
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }

    public function simpan_kontak()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $email = $this->request->getPost('email');
            $no_wa = $this->request->getPost('no_wa');
            $instagram = $this->request->getPost('instagram');

            $valid = $this->validate([
                'email' => [
                    'label' => 'E-mail',
                    'rules' => 'valid_email',
                    'errors' => [
                        'valid_email' => '{field} tidak valid'
                    ]
                ]
            ]);
            $validation = \Config\Services::validation();

            if ($valid) {
                $this->SettingModel->update($id, [
                    'email' => $email,
                    'no_wa' => $no_wa,
                    'instagram' => $instagram,
                ]);
                try {
                    $json = [
                        'success' => "Data kontak berhasil diubah"
                    ];
                } catch (\Throwable $th) {
                    $json = [
                        'error' => "Terdapat kesalahan pada sistem"
                    ];
                }
            } else {
                $json = [
                    'errors' => [
                        'email' => $validation->getError('error')
                    ]
                ];
            }
            echo json_encode($json);
        } else {
            exit("Tidak dapat diperoses");
        }
    }
}
