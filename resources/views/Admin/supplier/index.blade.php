@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="card-title mb-3 d-flex justify-content-between">
        <h4 class="card-title align-items-center mt-2">List Suppliers</h4>
        <button class="btn btn-primary btn-sm" onclick="addForm('{{ route('supplier.store') }}')">+
          Supplier</button>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table" id="table">
              <thead>
                <th width="5%">No</th>
                <th>Member Name</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th width="5%">Action</th>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-formLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-formLabel"></h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <form action="" method="POST" class="form form-horizontal">
            @csrf
            @method('post')
            <div class="form-body">
              <div class="row">
                <div class="">
                  <label for="nama">Name</label>
                </div>
                <div class="mt-2">
                  <input type="text" id="nama" class="form-control" name="nama" placeholder="Name" required
                    autofocus>
                  <span class="help-block with-errors"></span>
                </div>
                <div class="mt-2">
                  <label for="telepon">Phone</label>
                </div>
                <div class="mt-2">
                  <input type="text" id="telepon" class="form-control" name="telepon" placeholder="Product Name"
                    required autofocus>
                  <span class="help-block with-errors"></span>
                </div>
                <div class="mt-2">
                  <label for="alamat">Alamat</label>
                </div>
                <div class="mt-2">
                  <textarea id="alamat" class="form-control" name="alamat" placeholder="Harga Beli"autofocus></textarea>
                  <span class="help-block with-errors"></span>
                </div>
              </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- @includeIf('admin.category.form') --}}
@endsection

@push('scripts')
  <script>
    let table;

    $(function() {
      table = $('.table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
          url: '{{ route('supplier.data') }}',
        },
        columns: [{
            data: 'DT_RowIndex',
            searchable: false,
            sortable: false,
            class: 'text-center',
          },
          {
            data: 'nama'
          },
          {
            data: 'telepon'
          },
          {
            data: 'alamat'
          },
          {
            data: 'aksi',
            searchable: false,
            sortable: false
          },
        ]
      });

      $('#modal-form').validator().on('submit', function(e) {
        if (!e.preventDefault()) {
          $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
            .done((response) => {
              $('#modal-form').modal('hide');
              table.ajax.reload();
              showSwal('success');
            })
            .fail((errors) => {
              showSwal('error');
              return;
            });
        }
      });
    });

    function addForm(url) {
      $('#modal-form').modal('show');
      $('#modal-form .modal-title').text('Tambah Supplier');

      $('#modal-form form')[0].reset();
      $('#modal-form form').attr('action', url);
      $('#modal-form [name=_method]').val('post');
      $('#modal-form [name=nama]').focus();
    }

    function editForm(url) {
      $('#modal-form').modal('show');
      $('#modal-form .modal-title').text('Edit Supplier');

      $('#modal-form form')[0].reset();
      $('#modal-form form').attr('action', url);
      $('#modal-form [name=_method]').val('put');
      $('#modal-form [name=nama]').focus();

      $.get(url)
        .done((response) => {
          $('#modal-form [name=nama]').val(response.nama);
          $('#modal-form [name=telepon]').val(response.telepon);
          $('#modal-form [name=alamat]').val(response.alamat);
        })
        .fail((errors) => {
          alert('Tidak dapat menampilkan data');
          return;
        });
    }

    function deleteData(url) {
      showSwal('delete').then((result) => { // Call showSwal directly
        if (result) {
          $.post(url, {
              '_token': $('[name=csrf-token]').attr('content'),
              '_method': 'delete'
            })
            .done((response) => {
              table.ajax.reload();
              showSwal('s-delete');
            })
            .fail((errors) => {
              showSwal('error');
              return;
            });
        }
      });
    }
  </script>
@endpush
