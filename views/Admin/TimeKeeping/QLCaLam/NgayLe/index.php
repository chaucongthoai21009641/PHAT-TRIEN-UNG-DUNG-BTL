<link rel="stylesheet" href="{{ asset('css/ngayle.css') }}">

<div class="main-container d-flex">
    <div class="calendar-container" id="calendar-container"></div>

    <!-- Bảng bên phải -->
    <div class="right-panel">
        <div class="year-picker-container" onclick="toggleYearPicker()">
            <span>Year</span>
            <input type="text" id="yearSelect" readonly value="2025">
            <i class="fa fa-calendar"></i>
        </div>

        <div id="yearPicker" class="year-picker-popup">
            <div class="year-grid" id="yearGrid"></div>
            <div class="d-flex justify-content-between">
                <button onclick="changeYear(-10)">←</button>
                <button onclick="changeYear(10)">→</button>
            </div>
        </div>
        <div class="holiday-list" id="holiday-list"></div>
    </div>
</div>

<script>
    let currentYear = new Date().getFullYear();

    function createYearOptions() {
        const select = document.getElementById("yearSelect");
        const currentYear = new Date().getFullYear();

        for (let i = currentYear - 50; i <= currentYear + 50; i++) {
            let option = document.createElement("option");
            option.value = i;
            option.textContent = i;
            if (i === currentYear) option.selected = true;
            select.appendChild(option);
        }
    }


    function toggleYearPicker() {
        const yearPicker = document.getElementById("yearPicker");
        yearPicker.style.display = yearPicker.style.display === "block" ? "none" : "block";
        renderYearPicker(currentYear);
    }

    function renderYearPicker(startYear) {
        const yearGrid = document.getElementById("yearGrid");
        yearGrid.innerHTML = "";

        for (let i = startYear - 4; i <= startYear + 5; i++) {
            const yearDiv = document.createElement("div");
            yearDiv.classList.add("year");
            yearDiv.textContent = i;

            if (i === currentYear) {
                yearDiv.classList.add("selected");
            }

            yearDiv.onclick = function() {
                document.getElementById("yearSelect").value = i;
                currentYear = i;
                document.getElementById("yearPicker").style.display = "none";
                updateCalendar();
            };

            yearGrid.appendChild(yearDiv);
        }
    }

    function createCalendar(year) {
        const container = document.getElementById('calendar-container');
        container.innerHTML = "";
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        for (let month = 0; month < 12; month++) {
            let firstDay = new Date(year, month, 1).getDay();
            let lastDate = new Date(year, month + 1, 0).getDate();

            let calendarHTML = `
            <div class="calendar">
                <div class="calendar-header">${monthNames[month]}</div>
                <table>
                    <thead>
                        <tr>
                            <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
        `;

            let day = 1;
            for (let i = 0; i < 6; i++) {
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        calendarHTML += `<td></td>`;
                    } else if (day > lastDate) {
                        calendarHTML += `<td></td>`;
                    } else {
                        calendarHTML += `<td>${day}</td>`;
                        day++;
                    }
                }
                calendarHTML += `</tr><tr>`;
            }

            calendarHTML += `</tr></tbody></table></div>`;
            container.innerHTML += calendarHTML;
        }
    }

    function fetchHolidays(year) {
        // Dữ liệu giả lập từ API
        let holidays = {
            "2025": [{
                    name: "Nghỉ Tết Dương Lịch",
                    date: "01/01/2025"
                },
                {
                    name: "giải phóng",
                    date: "30/04/2025"
                },
                {
                    name: "Ngày quốc tế lao động",
                    date: "01/05/2025"
                }
            ],
            "2026": [{
                    name: "Nghỉ Tết Dương Lịch",
                    date: "01/01/2026"
                },
                {
                    name: "Ngày quốc tế phụ nữ",
                    date: "08/03/2026"
                },
                {
                    name: "Quốc khánh",
                    date: "02/09/2026"
                }
            ]
        };

        let holidayList = document.getElementById("holiday-list");
        holidayList.innerHTML = "";

        let data = holidays[year] || [];
        data.forEach(holiday => {
            let holidayItem = document.createElement("div");
            holidayItem.classList.add("holiday-item");

            let colorBox = document.createElement("div");
            colorBox.classList.add("holiday-color");

            let text = document.createElement("span");
            text.textContent = `${holiday.name} ${holiday.date}`;

            holidayItem.appendChild(colorBox);
            holidayItem.appendChild(text);
            holidayList.appendChild(holidayItem);
        });
    }


    function changeYear(delta) {
        currentYear += delta;
        renderYearPicker(currentYear);
    }

    function updateCalendar() {
        let selectedYear = document.getElementById("yearSelect").value;
        createCalendar(selectedYear);
        fetchHolidays(selectedYear);
    }

    updateCalendar();
</script>