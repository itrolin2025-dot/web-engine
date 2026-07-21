<x-app-layout>
    <!-- PAGE HEADER -->
    <div class="flex items-center space-x-4 py-5 lg:py-6">
    </div>

    @include('components.other') <!-- delete soon -->
    
    @include($modul_path . '.partials.table')

    @push('scripts')
    <script>
        function addToCustomer(id) {
            if (confirm('Are you sure you want to add this lead to Customer?')) {
                $.ajax({
                    url: "{{ url('leads/add-to-customer') }}/" + id, // Assuming route is /leads/add-to-customer/{id}
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            if (window.table) {
                                window.table.ajax.reload(null, false);
                            } else {
                                location.reload();
                            }
                        } else {
                            alert('Failed: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        alert('Something went wrong. Please try again.');
                    }
                });
            }
        }
    </script>
    @endpush

    @push('scripts')
    <script>
        $(document).ready(function() {
            var oldCity = "{{ old('city') }}";
            var provinceSelect = $('#province-select');
            
            // Trigger change on page load if province is selected (to reload cities if validation fails)
            if (provinceSelect.val()) {
                loadCities(provinceSelect.val(), oldCity);
            }

            provinceSelect.on('change', function() {
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
                    url: "{{ url('leads/get-cities') }}/" + encodeURIComponent(provinceName),
                    type: 'GET',
                    success: function(cities) {
                        var html = '<option value="">-- Select City --</option>';
                        cities.forEach(function(city) {
                            var isSelected = selectedCity === city.name ? 'selected' : '';
                            html += `<option value="${city.name}" ${isSelected}>${city.name}</option>`;
                        });
                        citySelect.html(html);
                    },
                    error: function() {
                        citySelect.html('<option value="">Error loading cities</option>');
                    }
                });
            }
        });
    </script>
    @endpush
    
</x-app-layout>
