<?php

return [
    'required' => ':attribute wajib diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'date' => ':attribute harus berupa tanggal yang valid.',
    'date_format' => 'Format :attribute tidak sesuai.',
    'after_or_equal' => ':attribute harus hari ini atau setelahnya.',
    'exists' => ':attribute yang dipilih tidak tersedia.',
    'unique' => ':attribute sudah digunakan.',
    'in' => ':attribute yang dipilih tidak valid.',
    'image' => ':attribute harus berupa gambar.',
    'mimes' => ':attribute harus berformat :values.',
    'regex' => 'Format :attribute tidak valid.',
    'confirmed' => 'Konfirmasi :attribute tidak sesuai.',
    'max' => ['string' => ':attribute maksimal :max karakter.', 'numeric' => ':attribute maksimal :max.', 'file' => ':attribute maksimal :max KB.'],
    'min' => ['string' => ':attribute minimal :min karakter.', 'numeric' => ':attribute minimal :min.'],
    'attributes' => [
        'name' => 'Nama', 'email' => 'Email', 'number_phone' => 'Nomor telepon', 'date' => 'Tanggal keberangkatan',
        'travel_package_id' => 'Paket wisata', 'location' => 'Destinasi', 'type' => 'Durasi perjalanan', 'price' => 'Harga',
        'description' => 'Deskripsi', 'title' => 'Judul', 'excerpt' => 'Ringkasan', 'image' => 'Foto', 'images' => 'Foto',
        'category_id' => 'Kategori', 'password' => 'Kata sandi', 'status' => 'Status',
    ],
];
