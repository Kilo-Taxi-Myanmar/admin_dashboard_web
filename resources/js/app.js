import "./bootstrap";
import "@fortawesome/fontawesome-free/css/all.css";
import axios from "axios";
import Pusher from "pusher-js";
import Swal from "sweetalert2";

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

Pusher.logToConsole = false;

var pusher = new Pusher("ff6d2dc3e07b1864a77d", {
    cluster: "ap1",
});

var channel = pusher.subscribe("topup-request-channel");
channel.bind("topup-request-noti-event", function (data) {
    Toast.fire({
        icon: "info",
        title: data.message,
    });
    topupRequest();
});

const noti_link = document.querySelector("#noti-api");
const noti_page_link = document.querySelector("#noti-pg");
const noti_url = noti_link.getAttribute("data-url");
const noti_page_url = noti_page_link.getAttribute("data-url");

const topupRequest = async () => {
    const res = await axios.get(noti_url);
    let i = 0;
    res.data.forEach((notification) => {
        if (notification.status == "unread") {
            i++;
        }
    });
    if (i != 0) {
        document.querySelector("#noti").innerHTML = `
            <a class="btn btn-danger px-2 py-1 rounded-5 text-white text-decoration-none"
    					href="${noti_page_url}">
    				<span class="small">${i} New</span>
    					<i class="fa-regular fa-bell"></i>
            </a>`;
    }
};
topupRequest();

const sidebar = document.querySelector("#sidebar");
const sidebar_btn = document.querySelector("#sidebar_btn");
const sidebar_btn2 = document.querySelector("#sidebar_btn2");
const section = document.querySelector("#section");

sidebar_btn.addEventListener("click", () => {
    sidebar.classList.remove("d-none");
    section.classList.add("d-none");
});

sidebar_btn2.addEventListener("click", () => {
    sidebar.classList.add("d-none");
    section.classList.remove("d-none");
});
