<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">
            @lang('site.create') @lang('site.sales')
        </h1>

        <div id="success-message" class="hidden bg-green-500 text-white p-2 mb-4 rounded"></div>

        <form id="sale-form" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.sale_date')</label>
                <input type="date" name="sale_date" id="sale_date" class="w-full border border-gray-300 rounded p-2">
                <span id="sale_date_error" class="text-red-500 text-sm"></span>
            </div>

            <table class="w-full border-collapse border border-gray-300 mb-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">@lang('site.sale_item')</th>
                        <th class="border p-2">@lang('site.quantity')</th>
                        <th class="border p-2">@lang('site.total_price')</th>
                        <th class="border p-2">@lang('site.is_taxable')</th>
                        <th class="border p-2">@lang('site.tax_rate')</th>
                        <th class="border p-2">@lang('site.actions')</th>
                    </tr>
                </thead>
                <tbody id="sale-rows">
                    <!-- سيتم إضافة الصفوف هنا -->
                </tbody>
            </table>

            <button type="button" id="add-row" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                + @lang('site.add_more')
            </button>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                @lang('site.create')
            </button>
        </form>
    </div>

    <script>

            // دالة لإنشاء صف جديد
            function addNewRow() {
                let newRow = `
                    <tr>
                        <td class="border p-2">
                            <select name="sale_item_id[]" class="w-full border p-2">
                                <option value="">@lang('site.select_item')</option>
                                @foreach ($saleItems as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="border p-2"><input type="number" name="quantity[]" class="w-full border p-2" min="1"></td>
                        <td class="border p-2"><input type="number" name="total_price[]" class="w-full border p-2" min="0"></td>
                        <td class="border p-2">
                            <select name="is_taxable[]" class="w-full border p-2">
                                <option value="0">@lang('site.no')</option>
                                <option value="1">@lang('site.yes')</option>
                            </select>
                        </td>
                        <td class="border p-2"><input type="number" name="tax_rate[]" class="w-full border p-2" min="0" max="100"></td>
                        <td class="border p-2 text-center">
                            <button type="button" class="remove-row bg-red-500 text-white px-3 py-1 rounded">X</button>
                        </td>
                    </tr>
                `;
                document.getElementById('sale-rows').insertAdjacentHTML('beforeend', newRow);

            }

            // تأكد من أن هناك على الأقل صف واحد عند تحميل الصفحة
        if (document.getElementById('sale-rows').children.length === 0) {
            addNewRow();
        }

        // زر الإضافة
        document.getElementById('add-row').addEventListener('click', addNewRow);

            // إزالة الصف عند النقر على زر X
            document.getElementById('sale-rows').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                if (document.getElementById('sale-rows').children.length === 0) {
                    addNewRow();
                }
            }
        });

            // إرسال البيانات باستخدام AJAX
            document.getElementById('sale-form').addEventListener('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(document.getElementById('sale-form'));

    fetch("{{ route('dashboard.daily_sales.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw err; // إجبار الخطأ للوصول إلى catch
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('success-message').innerText = data.message;
            document.getElementById('success-message').classList.remove('hidden');

            // إعادة تعيين النموذج وإضافة صف جديد بعد الإرسال
            document.getElementById('sale-form').reset();
            document.getElementById('sale-rows').innerHTML = '';
            addNewRow();
        }
    })
    .catch(error => {
        console.error('Error:', error);

        let errorMessage = "حدث خطأ غير متوقع!";

        // إذا كان الخطأ يحتوي على تفاصيل JSON من السيرفر
        if (typeof error === 'object' && error !== null) {
            if (error.message) {
                errorMessage = error.message;
            } else if (error.errors) {
                // استخراج الأخطاء من الـ validation
                errorMessage = Object.values(error.errors).flat().join("\n");
            } else {
                errorMessage = JSON.stringify(error);
            }
        }

        alert(errorMessage);
    });
});

    </script>
</x-app-layout>
