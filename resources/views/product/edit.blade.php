@extends('layout.admin')
@section('content')

    <body style="background: lightgray;">

        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm rounded">
                        <div class="card-body">
                            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label class="font-weight-bold">GAMBAR</label></br>
                                    <img src="{{ asset('/storage/product/' . $product->image) }}" alt=""
                                        style="width: 150px;">
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="form-group mt-2">
                                    <label class="font-weight-bold">Nama Product</label>
                                    <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" value="{{ old('product_name', $product->product_name) }}">
                                    <!-- error message untuk product_name -->
                                    @error('product_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <label class="font-weight-bold">Deskripsi Product</label>
                                    <textarea class="form-control @error('desc') is-invalid @enderror" name="desc">{{old('desc', $product->desc)}}</textarea>
                                    @error('desc')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <label class="font-weight-bold">Harga</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}">
                                    @error('price')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <label class="font-weight-bold">Size</label>
                                    <select class="form-control @error('size') is-invalid @enderror" name="size">
                                        <option value="">Pilih Size</option>
                                        <option value="S" {{ old('size', $pSize->size) == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('size', $pSize->size) == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('size', $pSize->size) == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('size', $pSize->size) == 'XL' ? 'selected' : '' }}>XL</option>
                                    </select>
                                    @error('size')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="form-group mt-2">
                                    <label class="font-weight-bold">Stok</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}">
                                    @error('stock')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <label class="font-weight-bold">Kategori</label>
                                    <select class="form-control @error('category') is-invalid @enderror" name="category" >
                                        <option value="">Pilih Kategori</option>
                                        <option value="new" {{ old('category', $product->category) == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="sale" {{ old('category', $product->category) == 'sale' ? 'selected' : ''}}>Sale</option>
                                    </select>
                                    @error('category')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="button mt-2">
                                    <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                                    <button type="reset" class="btn btn-md btn-warning">RESET</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    </body>
@endsection
