@extends('dashboard.layouts.main');

@section('container')
    <?php
    $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $bulan_full = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $daftar_hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];
    $romawi = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VII', 'IX', 'X', 'XI', 'XII'];
    
    if (fmod(count($income_taxes), 25) == 0) {
        $pageQty = count($income_taxes) / 25;
    } else {
        $pageQty = (count($income_taxes) - fmod(count($income_taxes), 25)) / 25 + 1;
    }
    
    $nominalObject = 0;
    $incomeType = [];
    ?>
    <div class="flex justify-center pl-14 py-10 bg-stone-800">
        <div class="z-0 mb-8 bg-stone-700 p-2 border rounded-md">
            <div class="flex justify-center w-full">
                <div class="w-[1550px]">
                    <div class="flex border-b">
                        <h1 class="index-h1">LIST PEMOTONGAN PPH</h1>
                        <div class="flex">
                            @canany(['isAdmin', 'isAccounting'])
                                @can('isCollect')
                                    @can('isAccountingRead')
                                        <button id="btnCreatePdf" class="flex justify-center items-center mx-1 btn-primary mb-2"
                                            title="Create PDF" type="button">
                                            <svg class="fill-current w-4 mx-1" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24">
                                                <path
                                                    d="M14 3h2.997v5h-2.997v-5zm9 1v20h-22v-24h17.997l4.003 4zm-17 5h12v-7h-12v7zm14 4h-16v9h16v-9z" />
                                            </svg>
                                            <span class="mx-1 text-white">Save PDF</span>
                                        </button>
                                    @endcan
                                @endcan
                            @endcanany
                        </div>
                    </div>
                    <form id="formFilter" action="/income-taxes/report/{{ $company->id }}">
                        <div class="flex">
                            <div class="flex h-14">
                                <div class="w-24">
                                    <span class="text-base text-stone-100">Bulan</span>
                                    <select name="month"
                                        class="p-1 outline-none border w-full text-md text-stone-900 rounded-md bg-stone-100"
                                        onchange="submit()">
                                        @if (request('month'))
                                            @for ($i = 1; $i < 13; $i++)
                                                @if ($i == request('month'))
                                                    <option value="{{ $i }}" selected>{{ $bulan_full[$i] }}
                                                    </option>
                                                @else
                                                    <option value="{{ $i }}">{{ $bulan_full[$i] }}</option>
                                                @endif
                                            @endfor
                                        @else
                                            @for ($i = 1; $i < 13; $i++)
                                                @if ($i == date('m'))
                                                    <option value="{{ $i }}" selected>{{ $bulan_full[$i] }}
                                                    </option>
                                                @endif
                                                <option value="{{ $i }}">{{ $bulan_full[$i] }}</option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                                <div class="ml-2 w-20">
                                    <span class="text-base text-stone-100">Tahun</span>
                                    <select name="year"
                                        class="p-1 text-center outline-none border w-full text-md text-stone-900 rounded-md bg-stone-100"
                                        onchange="submit()">
                                        @if (request('year'))
                                            @for ($i = date('Y'); $i > date('Y') - 5; $i--)
                                                @if ($i == request('year'))
                                                    <option value="{{ $i }}" selected>{{ $i }}
                                                    </option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        @else
                                            @for ($i = date('Y'); $i > date('Y') - 5; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="w-48 ml-2">
                                <span class="text-base text-stone-100">Pencarian</span>
                                <div class="flex">
                                    <input id="search" name="search"
                                        class="border rounded-l-lg p-1 outline-none text-md text-stone-900" type="text"
                                        placeholder="Search" value="{{ request('search') }}" onkeyup="submit()"
                                        onfocus="this.setSelectionRange(this.value.length, this.value.length);" autofocus>
                                    <button
                                        class="flex border p-1 rounded-r-lg text-slate-700 justify-center w-10 bg-slate-50"
                                        type="submit">
                                        <svg class="fill-current w-5" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex justify-center w-full">
                <div id="pdfPreview">
                    @if (count($income_taxes) == 0)
                        <div class="w-[1550px] h-[980px] bg-white p-8">
                            <div class="flex items-center border rounded-lg p-2 mt-6">
                                <div class="w-44">
                                    <img class="ml-2 w-[125px]" src="{{ asset('storage/' . $company->logo) }}"
                                        alt="">
                                </div>
                                <div class="w-[750px] ml-6">
                                    <div>
                                        <span class="text-md font-semibold">{{ $company->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-md">{{ $company->address }}, Desa/Kel. {{ $company->village }},
                                            Kec.
                                            {{ $company->district }}</span>
                                    </div>
                                    <div>
                                        <span class="text-md">{{ $company->city }} - {{ $company->province }}
                                            {{ $company->post_code }}</span>
                                    </div>
                                    <div>
                                        <span class="text-md">Ph. {{ $company->phone }} | Mobile.
                                            {{ $company->m_phone }}</span>
                                    </div>
                                    <div>
                                        <span class="text-md">e-mail : {{ $company->email }} | website :
                                            {{ $company->website }}</span>
                                    </div>
                                </div>
                                <div class="flex w-full justify-end">
                                    <div>
                                        <div class="flex items-end justify-center w-56">
                                            <label class="text-5xl text-center font-bold">-</label>
                                        </div>
                                        <div class="flex justify-center w-56">
                                            <label class="text-lg text-center font-bold">LIST PEMOTONGAN PPH</label>
                                        </div>
                                        <div class="flex justify-center w-56">
                                            <label class="text-md text-center"></label>
                                        </div>
                                        <div class="flex justify-center w-56 border rounded-md">
                                            @if (request('month'))
                                                <label class="month-report text-xl font-semibold text-center">
                                                    {{ $bulan_full[request('month')] }}
                                                    {{ request('year') }}
                                                </label>
                                            @else
                                                <label
                                                    class="month-report text-xl font-semibold text-center">{{ $bulan_full[(int) date('m')] }}
                                                    {{ date('Y') }}</label>
                                            @endif
                                        </div>
                                        <div class="flex justify-center w-56 border rounded-md mt-2">
                                            <label class="text-md">
                                                <span class="text-md font-semibold text-red-600">Tgl. Cetak :
                                                </span>
                                                {{ date('d') }} {{ $bulan[(int) date('m')] }}
                                                {{ date('Y') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center h-[875px] mt-2">
                                @if (request('month'))
                                    <label class="flex text-base text-red-600 font-serif tracking-wider">~~ Tidak ada data
                                        pemotongan PPH
                                        pada bulan
                                        {{ $bulan_full[request('month')] }}
                                        {{ request('year') }} ~~
                                    </label>
                                @else
                                    <label class="flex text-base text-red-600 font-serif tracking-wider">~~ Tidak ada data
                                        pemotongan PPH
                                        pada bulan
                                        {{ $bulan_full[(int) date('m')] }}
                                        {{ date('Y') }} ~~
                                    </label>
                                @endif
                            </div>
                        </div>
                    @else
                        @for ($i = 0; $i < $pageQty; $i++)
                            @if ($i == $pageQty - 1)
                                <div class="w-[1550px] h-[980px] bg-white p-8 mt-2">
                                    <div class="flex items-center border rounded-lg p-2 mt-6">
                                        <div class="w-44">
                                            <img class="ml-2 w-[125px]" src="{{ asset('storage/' . $company->logo) }}"
                                                alt="">
                                        </div>
                                        <div class="w-[750px] ml-6">
                                            <div>
                                                <span class="text-md font-semibold">{{ $company->name }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">{{ $company->address }}, Desa/Kel.
                                                    {{ $company->village }},
                                                    Kec.
                                                    {{ $company->district }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">{{ $company->city }} - {{ $company->province }}
                                                    {{ $company->post_code }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">Ph. {{ $company->phone }} | Mobile.
                                                    {{ $company->m_phone }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">e-mail : {{ $company->email }} | website :
                                                    {{ $company->website }}</span>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end">
                                            <div>
                                                <div class="flex items-end justify-center w-56">
                                                    <label class="text-5xl text-center font-bold">-</label>
                                                </div>
                                                <div class="flex justify-center w-56">
                                                    <label class="text-lg text-center font-bold">LIST PEMOTONGAN
                                                        PPH</label>
                                                </div>
                                                <div class="flex justify-center w-56">
                                                    <label class="text-md text-center"></label>
                                                </div>
                                                <div class="flex justify-center w-56 border rounded-md">
                                                    @if (request('month'))
                                                        <label class="month-report text-xl font-semibold text-center">
                                                            {{ $bulan_full[request('month')] }}
                                                            {{ request('year') }}
                                                        </label>
                                                    @else
                                                        <label
                                                            class="month-report text-xl font-semibold text-center">{{ $bulan_full[(int) date('m')] }}
                                                            {{ date('Y') }}</label>
                                                    @endif
                                                </div>
                                                <div class="flex justify-center w-56 border rounded-md mt-2">
                                                    <label class="text-md">
                                                        <span class="text-md font-semibold text-red-600">Tgl. Cetak :
                                                        </span>
                                                        {{ date('d') }} {{ $bulan[(int) date('m')] }}
                                                        {{ date('Y') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-[680px]">
                                        <table class="table-auto w-full mt-4">
                                            <thead>
                                                <tr class="bg-stone-200 h-8">
                                                    <th
                                                        class="text-stone-900 border border-black text-md w- text-center w-8">
                                                        No.</th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-24">
                                                        Masa
                                                    </th>
                                                    <th class="text-stone-900 border border-black text-md text-center">
                                                        Nama Pemotong
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-36">
                                                        NPWP
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-16">
                                                        Jenis
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-48">
                                                        Jenis Penghasilan
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-28">
                                                        Objek Potput
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-24">
                                                        PPH Potput
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-28">
                                                        No. Bukti
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-28">
                                                        Tgl.
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-36">
                                                        Alamat
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($income_taxes as $income_tax)
                                                    @if ($loop->iteration > $i * 25 && $loop->iteration < ($i + 1) * 25 + 1)
                                                        @php
                                                            $client = json_decode(
                                                                $income_tax->payment->billings[0]->client,
                                                            );
                                                            foreach ($income_tax->payment->billings as $billing) {
                                                                if ($billing->category == 'Service') {
                                                                    if (!in_array('Revisual', $incomeType)) {
                                                                        array_push($incomeType, 'Revisual');
                                                                    }
                                                                } elseif ($billing->category == 'Media') {
                                                                    if (!in_array('Sewa', $incomeType)) {
                                                                        array_push($incomeType, 'Sewa');
                                                                    }
                                                                }
                                                            }
                                                            $nominalObject =
                                                                $nominalObject +
                                                                $income_tax->payment->billings->sum('nominal');
                                                        @endphp
                                                        <tr class="h-[25px]">
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md  text-center">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md  text-center">
                                                                {{ $income_tax->payment->income_tax_document->period }}
                                                            </td>
                                                            <td class="text-stone-900 px-1 border border-black text-md">
                                                                @if (isset($client->company))
                                                                    @if (strlen($client->company) > 25)
                                                                        {{ substr($client->company, 0, 25) }}..
                                                                    @else
                                                                        {{ $client->company }}
                                                                    @endif
                                                                @elseif (isset($client->name))
                                                                    @if (strlen($client->name) > 25)
                                                                        {{ substr($client->name, 0, 25) }}..
                                                                    @else
                                                                        {{ $client->name }}
                                                                    @endif
                                                                @else
                                                                    @if (strlen($client->contact_name) > 25)
                                                                        {{ substr($client->contact_name, 0, 25) }}..
                                                                    @else
                                                                        {{ $client->contact_name }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-stone-900 border border-black text-md px-1 text-center">
                                                                @if (isset($client->npwp))
                                                                    {{ $client->npwp }}
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-stone-900 border border-black text-md px-1 text-center">
                                                                PPh 23
                                                            </td>
                                                            <td class="text-stone-900 border border-black text-md px-1">
                                                                @if (count($incomeType) == 1)
                                                                    {{ $incomeType[0] }} Reklame
                                                                @elseif(count($incomeType) == 2)
                                                                    {{ $incomeType[0] }} & {{ $incomeType[1] }} Reklame
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-stone-900  bg-red-50 px-1 border border-black text-md text-right">
                                                                {{ Number_format($income_tax->payment->billings->sum('nominal')) }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 bg-teal-50 px-1 border border-black text-md text-right ">
                                                                {{ number_format(round($income_tax->nominal)) }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md text-center">
                                                                {{ $income_tax->number }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md text-center">
                                                                {{ date('d', strtotime($income_tax->tax_date)) }}-{{ $bulan[(int) date('m', strtotime($income_tax->tax_date))] }}-{{ date('Y', strtotime($income_tax->tax_date)) }}
                                                            </td>
                                                            <td class="text-stone-900 px-1 border border-black text-md">
                                                                {{-- {{ $income_tax->client_city }} --}}
                                                                Jakarta Selatan
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr class="h-[25px]">
                                                    <td class="text-stone-900 px-1 border border-black text-md text-right font-semibold"
                                                        colspan="6">Total PPN</td>
                                                    <td
                                                        class="text-stone-900 bg-red-50 px-1 border border-black text-md text-right font-semibold">
                                                        {{ number_format($nominalObject) }}
                                                    </td>
                                                    <td
                                                        class="text-stone-900 px-1 border border-black text-md bg-teal-50 text-right font-semibold">
                                                        {{ number_format($income_taxes->sum('nominal')) }}
                                                    </td>
                                                    <td
                                                        class="text-stone-900 px-1 border border-black text-md  text-right bg-slate-400 font-semibold">
                                                    </td>
                                                    <td
                                                        class="text-stone-900 px-1 border border-black text-md  text-right bg-slate-400 font-semibold">
                                                    </td>
                                                    <td
                                                        class="text-stone-900 px-1 border border-black text-md  text-right bg-slate-400 font-semibold">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex items-end justify-end mt-1 text-black">
                                        <label for="">Halaman {{ $i + 1 }} dari
                                            {{ $pageQty }}</label>
                                    </div>
                                </div>
                            @else
                                <div class="w-[1550px] h-[980px] bg-white p-8 mt-2">
                                    <div class="flex items-center border rounded-lg p-2 mt-6">
                                        <div class="w-44">
                                            <img class="ml-2 w-[125px]" src="{{ asset('storage/' . $company->logo) }}"
                                                alt="">
                                        </div>
                                        <div class="w-[750px] ml-6">
                                            <div>
                                                <span class="text-md font-semibold">{{ $company->name }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">{{ $company->address }}, Desa/Kel.
                                                    {{ $company->village }},
                                                    Kec.
                                                    {{ $company->district }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">{{ $company->city }} - {{ $company->province }}
                                                    {{ $company->post_code }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">Ph. {{ $company->phone }} | Mobile.
                                                    {{ $company->m_phone }}</span>
                                            </div>
                                            <div>
                                                <span class="text-md">e-mail : {{ $company->email }} | website :
                                                    {{ $company->website }}</span>
                                            </div>
                                        </div>
                                        <div class="flex w-full justify-end">
                                            <div>
                                                <div class="flex items-end justify-center w-56">
                                                    <label class="text-5xl text-center font-bold">-</label>
                                                </div>
                                                <div class="flex justify-center w-56">
                                                    <label class="text-lg text-center font-bold">LIST PEMOTONGAN
                                                        PPH</label>
                                                </div>
                                                <div class="flex justify-center w-56">
                                                    <label class="text-md text-center"></label>
                                                </div>
                                                <div class="flex justify-center w-56 border rounded-md">
                                                    @if (request('month'))
                                                        <label class="month-report text-xl font-semibold text-center">
                                                            {{ $bulan_full[request('month')] }}
                                                            {{ request('year') }}
                                                        </label>
                                                    @else
                                                        <label
                                                            class="month-report text-xl font-semibold text-center">{{ $bulan_full[(int) date('m')] }}
                                                            {{ date('Y') }}</label>
                                                    @endif
                                                </div>
                                                <div class="flex justify-center w-56 border rounded-md mt-2">
                                                    <label class="text-md">
                                                        <span class="text-md font-semibold text-red-600">Tgl. Cetak :
                                                        </span>
                                                        {{ date('d') }} {{ $bulan[(int) date('m')] }}
                                                        {{ date('Y') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-[680px]">
                                        <table class="table-auto w-full mt-4">
                                            <thead>
                                                <tr class="bg-stone-200 h-8">
                                                    <th
                                                        class="text-stone-900 border border-black text-md w- text-center w-8">
                                                        No.</th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-24">
                                                        Masa
                                                    </th>
                                                    <th class="text-stone-900 border border-black text-md text-center">
                                                        Nama Pemotong
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-36">
                                                        NPWP
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-16">
                                                        Jenis
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-48">
                                                        Jenis Penghasilan
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-28">
                                                        Objek Potput
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-24">
                                                        PPH Potput
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-28">
                                                        No. Bukti
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-28">
                                                        Tgl.
                                                    </th>
                                                    <th
                                                        class="text-stone-900 border border-black text-md text-center w-36">
                                                        Alamat
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($income_taxes as $income_tax)
                                                    @if ($loop->iteration > $i * 25 && $loop->iteration < ($i + 1) * 25 + 1)
                                                        @php
                                                            $client = json_decode(
                                                                $income_tax->payment->billings[0]->client,
                                                            );
                                                            foreach ($income_tax->payment->billings as $billing) {
                                                                if ($billing->category == 'Service') {
                                                                    if (!in_array('Revisual', $incomeType)) {
                                                                        array_push($incomeType, 'Revisual');
                                                                    }
                                                                } elseif ($billing->category == 'Media') {
                                                                    if (!in_array('Sewa', $incomeType)) {
                                                                        array_push($incomeType, 'Sewa');
                                                                    }
                                                                }
                                                            }
                                                            $nominalObject =
                                                                $nominalObject +
                                                                $income_tax->payment->billings->sum('nominal');
                                                        @endphp
                                                        <tr class="h-[25px]">
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md  text-center">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md  text-center">
                                                                {{ $income_tax->payment->income_tax_document->period }}
                                                            </td>
                                                            <td class="text-stone-900 px-1 border border-black text-md">
                                                                @if (isset($client->company))
                                                                    @if (strlen($client->company) > 25)
                                                                        {{ substr($client->company, 0, 25) }}..
                                                                    @else
                                                                        {{ $client->company }}
                                                                    @endif
                                                                @elseif (isset($client->name))
                                                                    @if (strlen($client->name) > 25)
                                                                        {{ substr($client->name, 0, 25) }}..
                                                                    @else
                                                                        {{ $client->name }}
                                                                    @endif
                                                                @else
                                                                    @if (strlen($client->contact_name) > 25)
                                                                        {{ substr($client->contact_name, 0, 25) }}..
                                                                    @else
                                                                        {{ $client->contact_name }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-stone-900 border border-black text-md px-1 text-center">
                                                                @if (isset($client->npwp))
                                                                    {{ $client->npwp }}
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-stone-900 border border-black text-md px-1 text-center">
                                                                PPh 23
                                                            </td>
                                                            <td class="text-stone-900 border border-black text-md px-1">
                                                                @if (count($incomeType) == 1)
                                                                    {{ $incomeType[0] }} Reklame
                                                                @elseif(count($incomeType) == 2)
                                                                    {{ $incomeType[0] }} & {{ $incomeType[1] }} Reklame
                                                                @endif
                                                            </td>
                                                            <td
                                                                class="text-stone-900  bg-red-50 px-1 border border-black text-md text-right">
                                                                {{ Number_format($income_tax->payment->billings->sum('nominal')) }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 bg-teal-50 px-1 border border-black text-md text-right ">
                                                                {{ number_format(round($income_tax->nominal)) }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md text-center">
                                                                {{ $income_tax->number }}
                                                            </td>
                                                            <td
                                                                class="text-stone-900 px-1 border border-black text-md text-center">
                                                                {{ date('d', strtotime($income_tax->tax_date)) }}-{{ $bulan[(int) date('m', strtotime($income_tax->tax_date))] }}-{{ date('Y', strtotime($income_tax->tax_date)) }}
                                                            </td>
                                                            <td class="text-stone-900 px-1 border border-black text-md">
                                                                {{ $income_tax->client_city }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex justify-end mt-1 text-black">
                                        <label for="">Halaman {{ $i + 1 }} dari
                                            {{ $pageQty }}</label>
                                    </div>
                                </div>
                            @endif
                        @endfor
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if (request('month'))
        <input id="saveName" type="text"
            value="LIST PEMOTONGAN PPH - {{ $bulan_full[request('month')] }} {{ request('year') }}" hidden>
    @else
        <input id="saveName" type="text"
            value="LIST PEMOTONGAN PPH - {{ $bulan_full[(int) date('m')] }} {{ date('Y') }}" hidden>
    @endif


    <!-- Script start -->
    <script src="/js/html2canvas.min.js"></script>
    <script src="/js/html2pdf.bundle.min.js"></script>

    <script>
        const saveName = document.querySelectorAll("[id=saveName]");
        const pdfPreview = document.querySelectorAll("[id=pdfPreview]");
        document.getElementById("btnCreatePdf").onclick = function() {
            for (let i = 0; i < pdfPreview.length; i++) {
                var element = document.getElementById('pdfPreview');
                var opt = {
                    margin: 0,
                    filename: saveName[i].value,
                    image: {
                        type: 'jpeg',
                        quality: 1
                    },
                    pagebreak: {
                        mode: ['avoid-all', 'css', 'legacy']
                    },
                    html2canvas: {
                        dpi: 300,
                        scale: 2.5,
                        letterRendering: true,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'px',
                        format: [1550, 1000],
                        orientation: 'landscape',
                        putTotalPages: true
                    }
                };
                html2pdf().set(opt).from(element).save();
            }
        };
    </script>
    <!-- Script end -->
@endsection
