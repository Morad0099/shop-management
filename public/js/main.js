(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });

    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });

    // Fetch chart data
    $.ajax({
        url: '/dashboard/chart-data',
        method: 'GET',
        success: function (data) {
            // Populate Sales Overview Chart
            if ($('#sales-chart').length) {
                const salesCtx = $('#sales-chart').get(0).getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(data.monthlySales).map(month => `Month ${month}`),
                        datasets: [{
                            label: 'Sales (GHS)',
                            data: Object.values(data.monthlySales),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 2,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true },
                        },
                        scales: {
                            x: { display: true },
                            y: { display: true },
                        },
                    },
                });
            }

            // Populate Revenue by Product Chart
            if ($('#revenue-chart').length) {
                const revenueCtx = $('#revenue-chart').get(0).getContext('2d');
                new Chart(revenueCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(data.productRevenue),
                        datasets: [{
                            data: Object.values(data.productRevenue),
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                            hoverOffset: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                        },
                    },
                });
            }
        },
        error: function (err) {
            console.error('Error fetching chart data:', err);
        }
    });
    document.addEventListener('DOMContentLoaded', () => {
        const selects = document.querySelectorAll('.custom-select');
    
        selects.forEach((select) => {
            const searchInput = select.querySelector('.custom-search');
            const optionsList = select.querySelector('.custom-options');
            const hiddenInput = select.querySelector('input[type="hidden"]');
    
            // Toggle dropdown visibility
            searchInput.addEventListener('focus', () => {
                select.classList.add('open');
            });
    
            // Close dropdown on click outside
            document.addEventListener('click', (e) => {
                if (!select.contains(e.target)) {
                    select.classList.remove('open');
                }
            });
    
            // Filter options
            searchInput.addEventListener('input', () => {
                const filter = searchInput.value.toLowerCase();
                const options = optionsList.querySelectorAll('li');
    
                options.forEach((option) => {
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(filter) ? 'block' : 'none';
                });
            });
    
            // Select an option
            optionsList.addEventListener('click', (e) => {
                if (e.target.tagName === 'LI') {
                    const selectedValue = e.target.getAttribute('data-value');
                    const selectedText = e.target.textContent;
    
                    hiddenInput.value = selectedValue;
                    searchInput.value = selectedText;
    
                    select.classList.remove('open');
                }
            });
        });
    });
    
})(jQuery);
