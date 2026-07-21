<x-app-layout>
    <!-- PAGE HEADER -->
    <div class="flex items-center space-x-4 py-5 lg:py-6">
    </div>

    @include('components.other') <!-- delete soon -->

    @include('admin.' . $modul_path . '.partials.table')



    @push('scripts')
        <script>
            $(document).ready(function () {
                var oldCity = "{{ old('city') }}";
                var provinceSelect = $('#province-select');

                // Trigger change on page load if province is selected (to reload cities if validation fails)
                if (provinceSelect.val()) {
                    loadCities(provinceSelect.val(), oldCity);
                }

                provinceSelect.on('change', function () {
                    loadCities($(this).val());
                });

                function loadCities(provinceName, selectedCity = null) {
                    var citySelect = $('#city-select');
                    citySelect.html('<option value="">Loading...</option>');

                    if (!provinceName) {
                        citySelect.html('<option value="">-- Select City --</option>');
                        return;
                    }

                    $.ajax({
                        url: "{{ url('customers/get-cities') }}/" + encodeURIComponent(provinceName),
                        type: 'GET',
                        success: function (cities) {
                            var html = '<option value="">-- Select City --</option>';
                            cities.forEach(function (city) {
                                var isSelected = selectedCity === city.name ? 'selected' : '';
                                html += `<option value="${city.name}" ${isSelected}>${city.name}</option>`;
                            });
                            citySelect.html(html);
                        },
                        error: function () {
                            citySelect.html('<option value="">Error loading cities</option>');
                        }
                    });
                }
            });
        </script>
    @endpush

</x-app-layout>