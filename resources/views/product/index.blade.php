@extends('layout.admin')
@section('content')

<body style="background: lightgray;">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <hr>
                </div>
                <div class="card border-0 shadow-s rounded">
                    <div class="card-body">
                        <a href="{{ route('product.create') }}" class="btn btn-md btn-success mb-3">Tambah Produk</a>
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">GAMBAR</th>
                                    <th scope="col">NAMA</th>
                                    <th scope="col">DESKRIPSI</th>
                                    <th scope="col">HARGA</th>
                                    <th scope="col">STOK</th>
                                    <th scope="col">KATEGORI</th>
                                    <th scope="col">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($product as $menu)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ asset('/storage/product/' . $menu->image) }}" class="rounded"
                                                style="width: 150px;">
                                        </td>
                                        <td>{{ $menu->product_name }}</td>
                                        <td>{{ $menu->desc }}</td>
                                        <td>Rp. {{ number_format($menu->price) }}</td>
                                        <td>{{ $menu->stock }}</td>
                                        <td>{{ $menu->category }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('product.edit', $menu->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $menu->id }}">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <div class="alert alert-danger">
                                        Data Produk Belum Tersedia
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $product->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '.delete-btn', function() {
            var productId = $(this).data('id'); // Ambil ID produk dari data-id
            var url = "{{ route('product.destroy', ':id') }}"; // URL untuk penghapusan
            url = url.replace(':id', productId); // Ganti :id dengan ID produk

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda tidak dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan penghapusan menggunakan form POST
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}' // Kirimkan token CSRF
                        },
                        success: function(response) {
                            // Jika sukses, reload halaman atau tampilkan notifikasi
                            location.reload(); // Reload halaman untuk melihat perubahan
                        },
                        error: function(xhr) {
                            // Jika terjadi kesalahan
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat menghapus produk!'
                            });
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
@endsection
