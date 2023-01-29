


$(document).ready(function(){
    // Calculate Per Month Salary
    $("#calculateMonthly").click(function(){
        let workingHours = 8;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let workingDays = getDaysInMonth(currentMonth,currentYear);

        let perMonthSalary = $("#monthly_salary").val();
        let perHourSalary = parseFloat(perMonthSalary / workingDays.length / workingHours).toFixed(4) ;
        $("#hourly_salary").val(perHourSalary);
    });

    $(".calculateHourly").click(function(){
        console.log('clicked!')
        let workingHours = 8;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        let workingDays = getDaysInMonth(currentMonth,currentYear);
        let perMonthSalary = $("#monthly_salary2").val();
        let perHourSalary = parseFloat(perMonthSalary / workingDays.length / workingHours).toFixed(4) ;
        $("#hourly_salary2").val(perHourSalary);
    });

    function getDaysInMonth(month, year) {
       
        var date = new Date(year, month, 1);
        var days = [];
        while (date.getMonth() === month) {
    
            // Exclude weekends
            var tmpDate = new Date(date);            
            var weekDay = tmpDate.getDay(); 
            var day = tmpDate.getDate(); 
           
            if (weekDay !== 0) { 
                days.push(day);
            }
    
            date.setDate(date.getDate() + 1);
        }
    
        return days;
    } 
});