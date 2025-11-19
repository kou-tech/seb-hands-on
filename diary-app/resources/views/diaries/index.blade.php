@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <form method="GET" action="{{ route('diaries.index') }}" class="flex items-center gap-2">
            <select name="month" onchange="this.form.submit()" class="rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach ($months as $value => $label)
                    <option value="{{ $value }}" {{ $month === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('diaries.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
            + 新規作成
        </a>
    </div>

    @if ($diaries->isEmpty())
        <div class="text-center text-gray-500 py-8">
            この月の日記はありません
        </div>
    @else
        <div class="space-y-4">
            @foreach ($diaries as $diary)
                <a href="{{ route('diaries.edit', $diary) }}" class="block bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div class="text-sm text-gray-500 mb-2">
                            {{ $diary->date->format('n月j日') }}
                        </div>
                    </div>
                    <div class="text-gray-700 line-clamp-2">
                        {{ Str::limit($diary->content, 100) }}
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
