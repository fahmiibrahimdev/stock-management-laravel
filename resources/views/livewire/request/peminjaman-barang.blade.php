<div>
    <div class="section-header tw-rounded-lg tw-text-black tw-shadow-md">
        <h4 class="tw-text-lg">Peminjaman Barang</h4>
        <div class="px-1 ml-auto">
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" id="dropdown-toggle-filter" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-filter"></i> Filter
                </a>
                <div class="dropdown-menu px-3" id="dropdown-menu-filter">
                    <div class="form-group mt-2">
                        <label for="" class="tw-text-xs">Dari Tanggal :</label>
                        <input type="datetime-local" wire:model='dari_tanggal' class="tw-text-xs tw-border tw-border-gray-200 tw-rounded-lg">
                    </div>
                    <div class="form-group tw-mt-[-10px]">
                        <label for="" class="tw-text-xs">s/d Tanggal :</label>
                        <input type="datetime-local" wire:model='sampai_tanggal' class="tw-text-xs tw-border tw-border-gray-200 tw-rounded-lg">
                    </div>
                </div>
              </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-3">
                <div class="card tw-rounded-md tw-shadow-md">
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="id_user">Nama Peminjam</label>
                                <div wire:ignore>
                                    <select class="form-control tw-rounded-lg" wire:model='id_user' id="id_user">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_barang">Nama Barang</label>
                                <div wire:ignore>
                                    <select class="form-control tw-rounded-lg" wire:model='id_barang' id="id_barang">
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_pengawas">Nama Pengawas</label>
                                <div wire:ignore>
                                    <select class="form-control tw-rounded-lg" wire:model='id_pengawas' id="id_pengawas">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                <input type="datetime-local" wire:model='tanggal_pinjam' id="tanggal_pinjam" class="form-control tw-rounded-lg">
                            </div>
                            <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled"
                            class="btn btn-outline-success form-control">Save Data</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card card-primary tw-rounded-md tw-shadow-md">
                    <div class="card-body px-0">
                        <div class="row mb-3 px-4">
                            <div class="col-4 col-lg-2 tw-flex">
                                <select class="form-control tw-rounded-lg" wire:model='lengthData'>
                                    <option value="0" selected>All</option>
                                    <option value="1" selected>1</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                            <div class="col-8 col-lg-4 ml-auto tw-flex">
                                <span class="mt-2 text-dark mr-1 tw-hidden lg:tw-block">Search:</span>
                                <input wire:model="searchTerm" type="search" class="form-control tw-rounded-lg ml-auto"
                                    placeholder="Search here.." wire:model='searchTerm'>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="tw-table-fixed tw-w-full tw-text-black tw-text-md mt-3 tw-border-collapse tw-border">
                                <thead>
                                    <tr class="tw-border-b tw-text-xs text-center text-uppercase">
                                        <th class="p-3">
                                            Peminjam 
                                            <button class="bg-transparent float-right" wire:click.prevent="filterSort('{{ $filter_nama_peminjam }}', '{{ $filter_nama_barang }}', '{{ $filter_nama_pengawas }}', '{{ $filter_tanggal_pinjam }}', '{{ $filter_tanggal_kembali }}')">
                                                <i class="fas 
                                                        @if ($filter_nama_peminjam == "ASC")
                                                            fa-angle-down
                                                        @elseif ($filter_nama_peminjam == "DESC")
                                                            fa-angle-up
                                                        @endif 
                                                       ">
                                                </i>
                                            </button>
                                        </th>
                                        <th class="p-3">
                                            Nm Barang 
                                            <button class="bg-transparent float-right" wire:click.prevent="filterSort('{{ $filter_nama_peminjam }}', '{{ $filter_nama_barang }}', '{{ $filter_nama_pengawas }}', '{{ $filter_tanggal_pinjam }}', '{{ $filter_tanggal_kembali }}')">
                                                <i class="fas 
                                                        @if ($filter_nama_barang == "ASC")
                                                            fa-angle-down
                                                        @elseif ($filter_nama_barang == "DESC")
                                                            fa-angle-up
                                                        @endif 
                                                       ">
                                                </i>
                                            </button>
                                        </th>
                                        <th class="p-3">
                                            Pengawas 
                                            <button class="bg-transparent float-right" wire:click.prevent="filterSort('{{ $filter_nama_peminjam }}', '{{ $filter_nama_barang }}', '{{ $filter_nama_pengawas }}', '{{ $filter_tanggal_pinjam }}', '{{ $filter_tanggal_kembali }}')">
                                                <i class="fas 
                                                        @if ($filter_nama_pengawas == "ASC")
                                                            fa-angle-down
                                                        @elseif ($filter_nama_pengawas == "DESC")
                                                            fa-angle-up
                                                        @endif 
                                                       ">
                                                </i>
                                            </button>
                                        </th>
                                        <th class="p-3">
                                            Tgl Pinjam 
                                            <button class="bg-transparent float-right" wire:click.prevent="filterSort('{{ $filter_nama_peminjam }}', '{{ $filter_nama_barang }}', '{{ $filter_nama_pengawas }}', '{{ $filter_tanggal_pinjam }}', '{{ $filter_tanggal_kembali }}')">
                                                <i class="fas 
                                                        @if ($filter_tanggal_pinjam == "ASC")
                                                            fa-angle-down
                                                        @elseif ($filter_tanggal_pinjam == "DESC")
                                                            fa-angle-up
                                                        @endif 
                                                       ">
                                                </i>
                                            </button>
                                        </th>
                                        <th class="p-3">
                                            Tgl Kembali 
                                            <button class="bg-transparent float-right" wire:click.prevent="filterSort('{{ $filter_nama_peminjam }}', '{{ $filter_nama_barang }}', '{{ $filter_nama_pengawas }}', '{{ $filter_tanggal_pinjam }}', '{{ $filter_tanggal_kembali }}')">
                                                <i class="fas 
                                                        @if ($filter_tanggal_kembali == "ASC")
                                                            fa-angle-down
                                                        @elseif ($filter_tanggal_kembali == "DESC")
                                                            fa-angle-up
                                                        @endif 
                                                       ">
                                                </i>
                                            </button>
                                        </th>
                                        <th class="p-3 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $row)
                                    <tr
                                        class="tw-bg-white tw-border tw-text-uppercase tw-border-gray-200 hover:tw-bg-gray-50 text-center">
                                        <td class="p-3">
                                            {{ $row->nama_peminjam }}
                                            @if ($row->tanggal_kembali)
                                                <i class="fas fa-badge-check text-success"></i>
                                            @else
                                                
                                            @endif
                                        </td>
                                        <td class="p-3">{{ $row->nama_barang }}</td>
                                        <td class="p-3">{{ $row->nama_pengawas }}</td>
                                        <td class="p-3">{{ $row->tanggal_pinjam }}</td>
                                        <td class="p-3">
                                            @if ($row->tanggal_kembali == '')
                                                <button class="btn btn-success" wire:click.prevent="pengembalian({{ $row->id }})">
                                                    <i class="fas fa-badge-check"></i>
                                                </button>
                                            @else
                                                {{ $row->tanggal_kembali }}
                                            @endif
                                        </td>
                                        <td class="p-3 text-center">
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#ubahDataModal" wire:click="edit({{ $row->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger" wire:click.prevent="deleteConfirm({{ $row->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr> 
                                    @empty
                                    <tr class="text-center">
                                        <td class="p-3" colspan="6">
                                            No data available in table
                                        </td>
                                    </tr>    
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive p-3">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button
            class="tw-fixed tw-right-[30px] tw-bottom-[50px] tw-w-14 tw-h-14 tw-shadow-2xl tw-rounded-full tw-bg-slate-600 tw-z-40 text-white hover:tw-bg-slate-900 hover:tw-border-slate-600"
            data-toggle="modal" data-target="#tambahDataModal">
            <i class="far fa-plus"></i>
        </button>
    </div>

    <div class="modal fade" wire:ignore.self id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_user">Nama Peminjam</label>
                            <div wire:ignore>
                                <select class="form-control tw-rounded-lg" wire:model='id_user' id="id_user">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <div wire:ignore>
                                <select class="form-control tw-rounded-lg" wire:model='id_barang' id="id_barang">
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_pengawas">Nama Pengawas</label>
                            <div wire:ignore>
                                <select class="form-control tw-rounded-lg" wire:model='id_pengawas' id="id_pengawas">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="datetime-local" wire:model='tanggal_pinjam' id="tanggal_pinjam" class="form-control tw-rounded-lg">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Kembali</label>
                            <input type="datetime-local" wire:model='tanggal_kembali' id="tanggal_kembali" class="form-control tw-rounded-lg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled"
                            class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="ubahDataModal" aria-labelledby="ubahDataModalLabel"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahDataModalLabel">Edit Data</h5>
                    <button type="button" wire:click.prevent='cancel()' class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" wire:model='dataId'>
                        <div class="form-group">
                            <label for="edit_id_user">Nama Peminjam</label>
                            <div wire:ignore>
                                <select class="form-control tw-rounded-lg" wire:model='edit_id_user' id="edit_id_user">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_barang">Nama Barang</label>
                            <div wire:ignore>
                                <select class="form-control tw-rounded-lg" wire:model='edit_id_barang' id="edit_id_barang">
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_id_pengawas">Nama Pengawas</label>
                            <div wire:ignore>
                                <select class="form-control tw-rounded-lg" wire:model='edit_id_pengawas' id="edit_id_pengawas">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama_user }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="datetime-local" wire:model='tanggal_pinjam' id="tanggal_pinjam" class="form-control tw-rounded-lg">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Kembali</label>
                            <input type="datetime-local" wire:model='tanggal_kembali' id="tanggal_kembali" class="form-control tw-rounded-lg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent='cancel()' class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                        <button wire:click.prevent="update()" wire:loading.attr="disabled" type="button"
                            class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')

    <script>
        $(function() {

            $('#dropdown-toggle-filter').on('click', function(event) {
                $('#dropdown-menu-filter').slideToggle();
                event.stopPropagation();
            });

            $('#dropdown-menu-filter').on('click', function(event) {
                event.stopPropagation();
            });

            $(window).on('click', function() {
                $('#dropdown-menu-filter').slideUp();
            });

        });
        $(document).ready(function () {
            $('#id_user').select2();
            $('#id_barang').select2();
            $('#id_pengawas').select2();
            $('#edit_id_user').select2();
            $('#edit_id_barang').select2();
            $('#edit_id_pengawas').select2();
            
            $('#id_user').on('change', function (e) {
                var data = $('#id_user').select2("val");
                @this.set('id_user', data);
            });
            $('#id_barang').on('change', function (e) {
                var data = $('#id_barang').select2("val");
                @this.set('id_barang', data);
            });
            $('#id_pengawas').on('change', function (e) {
                var data = $('#id_pengawas').select2("val");
                @this.set('id_pengawas', data);
            });

            $('#edit_id_user').on('change', function (e) {
                var data = $('#edit_id_user').select2("val");
                @this.set('edit_id_user', data);
            });
            $('#edit_id_barang').on('change', function (e) {
                var data = $('#edit_id_barang').select2("val");
                @this.set('edit_id_barang', data);
            });
            $('#edit_id_pengawas').on('change', function (e) {
                var data = $('#edit_id_pengawas').select2("val");
                @this.set('edit_id_pengawas', data);
            });
        });
    </script>

@endpush