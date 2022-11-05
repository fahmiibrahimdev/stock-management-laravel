<div>
    <div class="section-header tw-rounded-lg tw-text-black tw-shadow-md">
        <h4 class="tw-text-lg">Barang Keluar</h4>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-3 tw-hidden">
                <div class="card tw-rounded-md tw-shadow-md">
                    <div class="card-body">
                    <form>
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="datetime-local" wire:model='tanggal' name="tanggal" id="tanggal" class="form-control tw-rounded-lg">
                            </div>
                            <div class="form-group">
                                <label for="id_barang">Nama Barang</label>
                                <div wire:ignore>
                                    <select wire:model='id_barang' name="id_barang" id="id_barang" class="form-control tw-rounded-lg">
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="qty_barang">Qty Barang</label>
                                        <input type="text" id="qty_barang" class="form-control text-center tw-rounded-lg" value="{{ $qty_barang }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="qty">Qty</label>
                                        <input type="text" wire:model='qty' name="qty" id="qty" class="form-control text-center tw-rounded-lg">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea wire:model='keterangan' name="keterangan" id="keterangan" style="height: 100px;" class="form-control"></textarea>
                            </div>
                            
                            <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled"
                            class="btn btn-outline-success form-control">Save Data</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card card-primary tw-rounded-md">
                    <div class="card-body px-0 tw-shadow-md">
                        <div class="row mb-3 px-4">
                            <div class="col-4 col-lg-2 tw-flex">
                                <select class="form-control" wire:model='lengthData'>
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
                                        <th class="p-3">Tanggal</th>
                                        <th class="p-3">Nama Barang</th>
                                        <th class="p-3">QTY</th>
                                        <th class="p-3">Keterangan</th>
                                        <th class="p-3 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $row)
                                    <tr
                                        class="tw-bg-white tw-border tw-text-uppercase tw-border-gray-200 hover:tw-bg-gray-50 text-center">
                                        <td class="p-3">{{ $row->tanggal }}</td>
                                        <td class="p-3">{{ $row->nama_barang }}</td>
                                        <td class="p-3">{{ $row->qty }}</td>
                                        <td class="p-3">{{ $row->keterangan }}</td>
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
                                        <td class="p-3" colspan="5">
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
                            <label for="tanggal">Tanggal</label>
                            <input type="datetime-local" wire:model='tanggal' name="tanggal" id="tanggal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <select wire:model='id_barang' name="id_barang" id="id_barang" class="form-control">
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="text" wire:model='qty' name="qty" id="qty" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea wire:model='keterangan' name="keterangan" id="keterangan" style="height: 100px;" class="form-control"></textarea>
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

    <div class="modal fade" wire:ignore.self id="ubahDataModal" tabindex="-1" aria-labelledby="ubahDataModalLabel"
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
                            <label for="tanggal">Tanggal</label>
                            <input type="datetime-local" wire:model='tanggal' name="tanggal" id="tanggal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <select wire:model='id_barang' name="id_barang" id="id_barang" class="form-control">
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="qty_barang">Qty Barang</label>
                                    <input type="text" id="qty_barang" class="form-control text-center tw-rounded-lg" value="{{ $qty_barang }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="text" wire:model='qty' name="qty" id="qty" class="form-control text-center tw-rounded-lg">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea wire:model='keterangan' name="keterangan" id="keterangan" style="height: 100px;" class="form-control"></textarea>
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
        $(document).ready(function () {
            $('#id_barang').select2();
            $('#id_barang').on('change', function (e) {
                var data = $('#id_barang').select2("val");
                @this.set('id_barang', data);
            });
        });
    </script>

@endpush
