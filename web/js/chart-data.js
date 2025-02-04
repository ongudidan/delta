"use strict";

function initializeCharts() {
  if ($("#apexcharts-area").length > 0) {
    // Destroy existing chart if present
    if (window.myApexChart) {
      window.myApexChart.destroy();
    }

    // Chart options with animation
    var options = {
      chart: {
        height: 350,
        type: "line",
        toolbar: { show: false },
        animations: { enabled: true, easing: "easeout", speed: 800 },
      },
      dataLabels: { enabled: false },
      stroke: { curve: "smooth", width: 2 },
      series: [
        { name: "Sales", color: "#3D5EE1", data: [] },
        { name: "Purchases", color: "#70C4CF", data: [] },
      ],
      xaxis: { categories: [] },
      yaxis: { labels: { formatter: (val) => val.toFixed(2) } },
    };

    // Initialize new chart
    window.myApexChart = new ApexCharts(
      document.querySelector("#apexcharts-area"),
      options
    );
    window.myApexChart.render();

    // Fetch data via AJAX
    $.ajax({
      url: "/dashboard/default/get-sales-and-purchases-by-month",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          var salesData = response.data.map((item) => item.sales);
          var purchasesData = response.data.map((item) => item.purchases);
          var monthNames = response.data.map((item) => item.month);

          // Animate chart update
          window.myApexChart.updateOptions({
            series: [
              { name: "Sales", color: "#3D5EE1", data: salesData },
              // { name: "Purchases", color: "#70C4CF", data: purchasesData }
            ],
            xaxis: { categories: monthNames },
            animations: { enabled: true, easing: "easeinout", speed: 1000 },
          });
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching sales data:", error);
      },
    });
  }

  if ($("#donut-chart").length > 0) {
    fetch("/dashboard/default/get-monthly-summary")
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          var donutChartOptions = {
            chart: {
              height: 350,
              type: "donut",
              toolbar: { show: false },
              animations: { enabled: true, easing: "easeout", speed: 800 },
            },
            series: [
              data.data.sales,
              data.data.purchases,
              data.data.expenses,
              data.data.net_profit,
            ],
            labels: ["Sales", "Purchases", "Expenses", "Net Profit"],
            responsive: [
              {
                breakpoint: 480,
                options: {
                  chart: { width: 200 },
                  legend: { position: "bottom" },
                },
              },
            ],
            colors: ["#3D5EE1", "#70C4CF", "#F34E4E", "#FFB64D"],
          };

          // Destroy old chart instance
          if (window.myDonutChart) {
            window.myDonutChart.destroy();
          }

          // Render new chart with animation
          window.myDonutChart = new ApexCharts(
            document.querySelector("#donut-chart"),
            donutChartOptions
          );
          window.myDonutChart.render();
        }
      })
      .catch((error) => console.error("Fetch error:", error));
  }
}

// Run on initial page load
$(document).ready(initializeCharts);

// Re-run on PJAX update (ensures animations after PJAX)
$(document).on("pjax:end", initializeCharts);
