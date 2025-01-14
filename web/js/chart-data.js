"use strict";
$(document).ready(function () {

 if ($("#apexcharts-area").length > 0) {
   // Initialize chart options
   var options = {
     chart: { height: 350, type: "line", toolbar: { show: false } },
     dataLabels: { enabled: false },
     stroke: { curve: "smooth" },
     series: [
       {
         name: "Sales",
         color: "#3D5EE1",
         data: [], // Empty initially, will be updated with AJAX data
       },
       {
         name: "Purchases",
         color: "#70C4CF",
         data: [], // Empty initially, will be updated with AJAX data
       },
     ],
     xaxis: {
       categories: [], // Month names, will be updated dynamically
     },
   };

   // Initialize chart
   var chart = new ApexCharts(
     document.querySelector("#apexcharts-area"),
     options
   );
   chart.render();

   // Fetch data via AJAX
   $.ajax({
     url: "/dashboard/default/get-sales-and-purchases-by-month", // Replace with the correct endpoint URL
     type: "GET",
     dataType: "json",
     success: function (response) {
       if (response.status === "success") {
         // Extract data for the chart
         var salesData = response.data.map((item) => item.sales);
         var purchasesData = response.data.map((item) => item.purchases);
         var monthNames = response.data.map((item) => item.month);

         console.log(salesData, purchasesData);

         // Update chart options with fetched data
         chart.updateOptions({
           series: [
             {
               name: "Sales",
               color: "#3D5EE1",
               data: salesData,
             },
             {
               name: "Purchases",
               color: "#70C4CF",
               data: purchasesData,
             },
           ],
           xaxis: {
             categories: monthNames,
           },
         });
       } else {
         console.error("Failed to fetch sales and purchases data.");
       }
     },
     error: function (xhr, status, error) {
       console.error("Error fetching sales and purchases data:", error);
     },
   });
 }


});

// if ($("#donut-chart").length > 0) {
//   // Fetch data using AJAX
//   fetch("/dashboard/default/get-monthly-summary")
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.status === "success") {
//         // Prepare the chart data
//         var donutChart = {
//           chart: { height: 350, type: "donut", toolbar: { show: false } },
//           series: [
//             data.data.sales, // Total Sales
//             data.data.purchases, // Total Purchases
//             data.data.expenses, // Total Expenses
//           ],
//           labels: ["Sales", "Purchases", "Expenses"], // Labels for the chart
//           responsive: [
//             {
//               breakpoint: 480,
//               options: {
//                 chart: { width: 200 },
//                 legend: { position: "bottom" },
//               },
//             },
//           ],
//           colors: ["#3D5EE1", "#70C4CF", "#F34E4E"], // Custom colors
//         };

//         // Render the chart
//         var donut = new ApexCharts(
//           document.querySelector("#donut-chart"),
//           donutChart
//         );
//         donut.render();
//       } else {
//         console.error("Error fetching data:", data.message);
//       }
//     })
//     .catch((error) => {
//       console.error("Fetch error:", error);
//     });
// }

if ($("#donut-chart").length > 0) {
  // Fetch data using AJAX
  fetch("/dashboard/default/get-monthly-summary")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Prepare the chart data
        var donutChart = {
          chart: { height: 350, type: "donut", toolbar: { show: false } },
          series: [
            data.data.sales, // Total Sales
            data.data.purchases, // Total Purchases
            data.data.expenses, // Total Expenses
            data.data.net_profit, // Net Profit
          ],
          labels: ["Sales", "Purchases", "Expenses", "Net Profit"], // Labels for the chart
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: { width: 200 },
                legend: { position: "bottom" },
              },
            },
          ],
          colors: ["#3D5EE1", "#70C4CF", "#F34E4E", "#FFB64D"], // Custom colors including Net Profit
        };

        // Render the chart
        var donut = new ApexCharts(
          document.querySelector("#donut-chart"),
          donutChart
        );
        donut.render();
      } else {
        console.error("Error fetching data:", data.message);
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
}


