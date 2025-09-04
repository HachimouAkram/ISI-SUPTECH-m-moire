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
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });


    // Progress Bar
    $('.pg-bar').waypoint(function () {
        $('.progress .progress-bar').each(function () {
            $(this).css("width", $(this).attr("aria-valuenow") + '%');
        });
    }, {offset: '80%'});


    // Calender
    $('#calender').datetimepicker({
        inline: true,
        format: 'L'
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        items: 1,
        dots: true,
        loop: true,
        nav : false
    });


    // Chart Global Color
    Chart.defaults.color = "#6C7293";
    Chart.defaults.borderColor = "#000000";


    // Bar Chart - Évolution des inscriptions par département
    var ctx1 = $("#worldwide-sales").get(0).getContext("2d");
    var myChart1 = new Chart(ctx1, {
        type: "bar",
        data: {
            labels: ["2020-2021", "2021-2022", "2022-2023", "2023-2024", "2024-2025", "2025-2026"],
            datasets: [
                {
                    label: "Gestion",
                    data: [45, 60, 75, 80, 95, 110],
                    backgroundColor: "rgba(54, 162, 235, 0.7)"
                },
                {
                    label: "Informatique",
                    data: [90, 125, 155, 185, 210, 240],
                    backgroundColor: "rgba(255, 99, 132, 0.7)"
                }
            ]
        },
        options: {
            responsive: true
        }
    });


    // Line Chart - Évolution des inscriptions par département
    var ctx2 = $("#salse-revenue").get(0).getContext("2d");
    var myChart2 = new Chart(ctx2, {
        type: "line",
        data: {
            labels: ["2020-2021", "2021-2022", "2022-2023", "2023-2024", "2024-2025", "2025-2026"],
            datasets: [
                {
                    label: "Gestion",
                    data: [45, 60, 75, 80, 95, 110],
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    fill: true
                },
                {
                    label: "Informatique",
                    data: [90, 125, 155, 185, 210, 240],
                    backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    fill: true
                }
            ]
        },
        options: {
            responsive: true
        }
    });


    // Bar Chart - Inscriptions 2025–2026 par département
    var ctx4 = $("#bar-chart").get(0).getContext("2d");
    var myChart4 = new Chart(ctx4, {
        type: "bar",
        data: {
            labels: ["Gestion", "Informatique"],
            datasets: [{
                label: "Inscriptions 2025–2026",
                backgroundColor: [
                    "rgba(54, 162, 235, 0.7)",
                    "rgba(255, 99, 132, 0.7)"
                ],
                data: [110, 240]
            }]
        },
        options: {
            responsive: true
        }
    });


    // Pie Chart - Répartition des inscriptions 2025–2026
    var ctx5 = $("#pie-chart").get(0).getContext("2d");
    var myChart5 = new Chart(ctx5, {
        type: "pie",
        data: {
            labels: ["Gestion", "Informatique"],
            datasets: [{
                backgroundColor: [
                    "rgba(54, 162, 235, 0.7)",
                    "rgba(255, 99, 132, 0.7)"
                ],
                data: [110, 240]
            }]
        },
        options: {
            responsive: true
        }
    });


    // Doughnut Chart - Répartition des inscriptions 2025–2026
    var ctx6 = $("#doughnut-chart").get(0).getContext("2d");
    var myChart6 = new Chart(ctx6, {
        type: "doughnut",
        data: {
            labels: ["Gestion", "Informatique"],
            datasets: [{
                backgroundColor: [
                    "rgba(54, 162, 235, 0.7)",
                    "rgba(255, 99, 132, 0.7)"
                ],
                data: [110, 240]
            }]
        },
        options: {
            responsive: true
        }
    });


})(jQuery);


