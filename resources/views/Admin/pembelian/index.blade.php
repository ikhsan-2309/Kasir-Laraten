@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="card-title mb-3 d-flex justify-content-between align-items-center">
        <h4 class="card-title me-auto">List Purchases</h4>
        <div class="d-flex">
          @empty(!session('id_pembelian'))
            <a href="{{ route('pembelian_detail.index') }}"" class="btn btn-success btn-icon-text btn-sm m-2">
              <i class="fa-solid fa-history btn-icon-prepend"></i>
              Last Purchase
            </a>
          @endempty
          <button type="button" class="btn btn-primary btn-icon-text btn-sm mt-2 mb-2" onclick="addForm()">
            <i class="fa-solid fa-plus btn-icon-prepend"></i>
            Purchase
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table" id="table-pembelian">
              <thead>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Total Item</th>
                <th>Total Harga (Rp)</th>
                <th>Diskon (%)</th>
                <th>Total Bayar (Rp)</th>
                <th width="5%" class="text-center"><i class="fa fa-cog"></i></th>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-formLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-body pt-1">
          <div class="table-responsive">
            <table class="table" id="table-detail">
              <thead>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal (Rp)</th>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-labelledby="modal-formLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-formLabel">Pilih Supplier</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-1">
          <div class="table-responsive">
            <table class="table table-sup" id="table">
              <thead>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th width="5%">Action</th>
              </thead>
              <tbody>
                @foreach ($supplier as $key => $item)
                  <tr>
                    <td width="5%">{{ $key + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>
                      <a href="{{ route('pembelian.create', $item->id_supplier) }}"
                        class="btn btn-primary btn-xs btn-flat">
                        <i class="fa fa-check-circle"></i>
                        Pilih
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    let table, table1;

    $(function() {
      table = $('#table-pembelian').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: true,
        ajax: {
          url: '{{ route('pembelian.data') }}',
        },
        columns: [{
            data: 'DT_RowIndex',
            searchable: false,
            sortable: false,
            class: 'text-center',
          },
          {
            data: 'tanggal',
          },
          {
            data: 'supplier',
          },
          {
            data: 'total_item',
            class: 'text-center',
          },
          {
            data: 'total_harga',
            class: 'text-center',
          },
          {
            data: 'diskon',
            class: 'text-center',
          },
          {
            data: 'bayar',
            class: 'text-center',
          },
          {
            data: 'aksi',
            searchable: false,
            sortable: false
          },
        ]
      });

      $('.table-supplier').DataTable();
      table1 = $('#table-detail').DataTable({
        responsive: true,
        processing: true,
        autoWidth: true,
        bSort: false,
        dom: 'Brt',
        columns: [{
            data: 'DT_RowIndex',
            searchable: false,
            sortable: false,
            class: 'text-center'
          },
          {
            data: 'kode_produk'
          },
          {
            data: 'nama_produk'
          },
          {
            data: 'harga_beli'
          },
          {
            data: 'jumlah'
          },
          {
            data: 'subtotal'
          },
        ]
      })
    });

    function addForm() {
      $('#modal-supplier').modal('show');
    }

    function showDetail(url) {
      $('#modal-detail').modal('show');

      table1.ajax.url(url);
      table1.ajax.reload();
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
