$(document).ready(function () {
    let activeTab = localStorage.getItem("activeTab") || "#thoikhoabieu"; // Mặc định tab đầu tiên
    let loadedScripts = {}; // Lưu script đã tải

    function loadScript(scriptUrl) {
        if (scriptUrl && !loadedScripts[scriptUrl]) {
            $.getScript(scriptUrl)
                .done(function () {
                    console.log("Script loaded:", scriptUrl);
                })
                .fail(function () {
                    console.error("Error loading script:", scriptUrl);
                });
            loadedScripts[scriptUrl] = true; // Đánh dấu script đã tải
        }
    }

    function handleTabClick(event) {
        let selectedTab = $(this).attr("href");
        let scriptUrl = $(this).data("script");

        localStorage.setItem("activeTab", selectedTab);
        loadScript(scriptUrl);
    }

    // Kích hoạt tab mặc định ngay khi trang tải xong
    let activeTabElement = $(`a[href="${activeTab}"]`);
    if (activeTabElement.length) {
        activeTabElement.tab("show"); // Kích hoạt tab
        let scriptUrl = activeTabElement.data("script");
        loadScript(scriptUrl); // Tải script ngay lập tức
    }

    // Lắng nghe sự kiện click trên tất cả các tab
    $(".nav-link").on("click", handleTabClick);
});




// document.addEventListener("DOMContentLoaded", function() {
//     let activeTab = localStorage.getItem("activeTab") || "#thoikhoabieu";
//     let loadedScripts = {}; // Lưu script đã tải

//     function loadScript(scriptUrl) {
//         if (scriptUrl && !loadedScripts[scriptUrl]) {
//             let script = document.createElement("script");
//             script.src = scriptUrl;
//             script.defer = true;
//             document.body.appendChild(script);
//             loadedScripts[scriptUrl] = true; // Đánh dấu script đã tải
//         }
//     }

//     function handleTabClick(event) {
//         let selectedTab = event.target.getAttribute("href");
//         let scriptUrl = event.target.getAttribute("data-script");

//         localStorage.setItem("activeTab", selectedTab);
//         new bootstrap.Tab(event.target).show();
//         loadScript(scriptUrl);
//     }

//     // Kích hoạt tab đã lưu và load script tương ứng
//     let activeTabElement = document.querySelector(`a[href="${activeTab}"]`);
//     if (activeTabElement) {
//         new bootstrap.Tab(activeTabElement).show();
//         loadScript(activeTabElement.getAttribute("data-script"));

//         activeTabElement.dispatchEvent(new Event("click"));
//     }

//     document.querySelectorAll(".nav-link").forEach(tab => {
//         tab.addEventListener("click", handleTabClick);
//     });
// });