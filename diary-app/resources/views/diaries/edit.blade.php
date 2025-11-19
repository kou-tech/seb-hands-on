@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">日記を編集</h2>

        <form method="POST" action="{{ route('diaries.update', $diary) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">日付</label>
                <input type="date" name="date" id="date" value="{{ old('date', $diary->date->format('Y-m-d')) }}"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">内容</label>
                <textarea name="content" id="content" rows="8"
                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('content', $diary->content) }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('diaries.index') }}" class="text-gray-600 hover:text-gray-800">
                    ← 戻る
                </a>

                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('delete-form').submit()"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
                        削除
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow">
                        保存
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" method="POST" action="{{ route('diaries.destroy', $diary) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection
