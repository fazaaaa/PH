@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/backend/assets/vendor/select2/select2.min.css')}}">
@endsection

@section('js')
    <script src="{{asset('assets/backend/assets/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('assets/backend/assets/js/components/select2-init.js')}}"></script>
    <script>
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    </script>
@endsection
@section('content')
<section class="page-content container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <center>
                    <div class="card-header">Tambah Data Penerima Bantuan</div>
                    </center>
                        <div class="card-body">
                            <form action="{{route('penban.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input class="form-control{{ $errors->has('nik') ? ' has-error' : '' }}" type="text"
                                    name="nik" id="" required>
                                   @if ($errors->has('nik'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nik') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">NO KK</label>
                                    <input class="form-control allownumericwithoutdecimal{{ $errors->has('no_kk') ? ' has-error' : '' }}" type="text"
                                    name="no_kk" id="" required>
                                   @if ($errors->has('no_kk'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('no_kk') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input class="form-control{{ $errors->has('nama') ? ' has-error' : '' }}" type="text"
                                    name="nama" id="" required>
                                   @if ($errors->has('nama'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Status Pekerjaan</label>
                                    <input class="form-control{{ $errors->has('status_pkj') ? ' has-error' : '' }}" type="text"
                                    name="status_pkj" id="" required>
                                   @if ($errors->has('status_pkj'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('status_pkj') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <input class="form-control number {{ $errors->has('jk') ? ' has-error' : '' }}" type="text"
                                    name="jk" id="" required>
                                   @if ($errors->has('jk'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jk') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Bantuan</label>
                                    <input class="form-control number {{ $errors->has('jb') ? ' has-error' : '' }}" type="text"
                                    name="jb" id="" required>
                                   @if ($errors->has('jb'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jb') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Foto Pribadi</label>
                                    <input class="form-control{{ $errors->has('foto_diri') ? ' has-error' : '' }}" type="file"
                                    name="foto_diri" id="" required>
                                   @if ($errors->has('foto_diri'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('foto_diri') }}</strong>
                                </span>
                            @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-info btn-rounded btn-block">
                                        Simpan Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection