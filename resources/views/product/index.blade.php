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
                                            <td>Rp. {{ number_format($menu->harga) }}</td>
                                            <td>{{ $menu->stok }}</td>
                                            <td>{{ $menu->kategori }}</td>
                                            <td class="text-center">
                                                <form onsubmit="return confirm('Apakah Anda Yakin ?')"
                                                    action="{{ route('product.destroy', $menu->id) }}" method="POST">
                                                    <a href="{{ route('product.edit', $menu->id) }}"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
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
    </body>

    </html>
@endsection
