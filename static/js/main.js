const toggleDatePicker = (self) => {
  const x = document.querySelector("#date-plc");
  document.querySelector("#date").value = self.value;
  x.innerHTML = self.value.replaceAll("-", "/");
};
const deleteItem = (id, table) => {
  const c = confirm("You are realy want to delete this item");
  if (c) {
    window.location.href = window.location.href + "&d=" + id + "&t=" + table;
  }
};
let weather = {};
const Cookies = {
  remove: (name) => {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    return 1;
  },
  set: (name, value, options = { expires: 1, maxAge: "", path: "/" }) => {
    options = options ? options : {};
    if (options?.expires)
      options.expires = new Date(
        Date.now() + 86400000 * Number(options.expires)
      );
    options.path = options.path ? options.path : "/";
    options.secure = options.secure ? options.secure : false;
    let cookies = `${name}=${value}`;
    for (const option in options) {
      if (options.hasOwnProperty(option)) {
        if (option === "maxAge") cookies += `;max-age=${options[option]}`;
        // @ts-ignore
        else cookies += `;${option}=${options[option]}`;
      }
    }
    document.cookie = cookies;
    return 1;
  },
  get: (name) => {
    let cookies = document.cookie.split(";");
    let value = null;
    for (const c of cookies) {
      let [n, v] = c.split("=");
      if (n.trim() === name) return decodeURIComponent(v.trim());
    }
    return value;
  },
};
const submitVisaForm = async () => {
  const form = document.getElementById("visa-form");
  const formData = new FormData();
  let fields = [
    "user_id",
    "name",
    "nationality",
    "gender",
    "dob",
    "martial",
    "profession",
    "passport_no",
    "passport_type",
    "date_of_issue",
    "expiry_date",
    "other_name",
    "permanent_addr",
    "phone",
    "purpose_of_visit",
    "duration_of_visa_req",
    "departure_date",
    "stay_period",
    "reference_in_yemen",
  ];
  let isValid = true;
  for (let f of fields) {
    if (form[f].value.trim() === "") {
      let name = f === "dob" ? "date of birth" : f.replace("_", " ");
      createAlert(
        form[f],
        `Please fill ${name} field. ${name} is required.`,
        "alert"
      );
      isValid = false;
      break;
    } else {
      formData.append(f, form[f].value);
    }
  }
  if (isValid) {
    formData.append("save-visa", 1);
    if (form["form-type"].value === "update")
      formData.append("form-id", form["form-id"].value);
    formData.append("form-type", form["form-type"].value);
    await fetch("/api/set-data.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((res) => {
        if (res === "success") {
          createAlert(
            "alert-text",
            form["form-type"].value === "update"
              ? "Updated successfully!"
              : "Your form is submitted successfully. we will inform you in next step",
            "success"
          );
        }
      })
      .catch((e) => createAlert("alert-text", e.message, "danger"));
  }
};
const signOut = () => {
  if (Cookies.remove("user")) {
    createAlert("alert-text", "Sign out successfully.");
    window.location.href = "/?p=login";
  }
};
const signIn = async () => {
  const form = document.getElementById("sign-in-form");
  const email = form["email"];
  const password = form["password"];
  if (email.value === "") {
    createAlert(email, "Please enter your email address.", "alert");
    return 0;
  }
  if (password.value === "") {
    createAlert(password, "Please enter your password.", "alert");
    return 0;
  }
  const formData = new FormData();
  formData.append("email", email.value);
  formData.append("password", password.value);
  formData.append("signin", 1);
  await fetch("/api/get-data.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.success === 0) {
        createAlert("alert-text", res.message, "alert");
      } else if (res.id) {
        Cookies.set("user", JSON.stringify(res));
        createAlert("alert-text", "Succesfully logged!", "success");
        let r = window.location.href;
        let uri = new URLSearchParams(r);
        if (uri.get("r")) {
           window.location.href = `/?p=${uri.get("r")}${uri.get('id') ? "&id=" + uri.get('id') : ''}`;
        }
        else window.location.href = "/";
      }
    })
    .catch((e) => createAlert("alert-text", "Error: " + e.message, "danger"));
};
const postSignUp = async (data) => {
  const form = new FormData();
  form.append("signup", 1);
  for (let item in data) {
    if (data[item]) form.append(item, data[item]);
  }
  await fetch("/api/set-data.php", {
    method: "POST",
    body: form,
  })
    .then((res) => res.json())
    .then((res) => {
      createAlert("alert-text", res.message, res.success ? "success" : "alert");
      if (res.success) {
        Cookies.set(
          "user",
          JSON.stringify({
            name: res.name,
            email: res.email,
            avatar: res.avatar,
            id: Number(res.id),
          }),
          { expires: 31 }
        );
        window.location.href = "/";
      }
    })
    .catch((e) => createAlert("alert-text", "Error: " + e.message, "danger"));
};
const submitSignUp = () => {
  const form = document.getElementById("signup-form");
  let fullname = form["fullname"];
  let email = form["email"];
  let avatar = form["avatar"];
  let password = form["password"];
  let repassword = form["re-password"];
  let root_path = form["root_path"].value;
  let f = false;
  if (fullname.value === "")
    f = createAlert(fullname, "Please Enter your fullname.", "alert");
  else if (email.value === "")
    f = createAlert(email, "Please Enter your email address.", "alert");
  else if (password.value === "") {
    f = createAlert(password, "Please Enter your password.", "alert");
  } else if (password.value !== "" && password.value !== repassword.value) {
    f = createAlert(
      repassword,
      "Password is not matched please confirm your password.",
      "alert"
    );
  }
  if (f) {
    return 0;
  } else {
    postSignUp({
      fullname: fullname.value,
      email: email.value,
      avatar: avatar.files[0],
      password: password.value,
      root_path,
    });
  }
};
const createAlert = (id, message, variant, time = 5000) => {
  let currElement = id;
  if (typeof id === "string") currElement = document.getElementById(id);
  const span = document.createElement("span");
  span.classList.add("message");
  span.classList.add(variant);
  span.innerText = message;
  currElement.parentElement.appendChild(span);
  span.scrollIntoView({ behavior: "smooth", block: "center" });
  currElement.focus();
  setTimeout(() => {
    span.remove();
  }, time);
  return 1;
};
const handleImage = (element, multiple = 0) => {
  multiple = 1;
  if (element.files && element.files[0]) {
    let files = [];
    if (multiple) {
      files = element.files;
    } else {
      files = element.files[0];
    }
    let allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
    if (!multiple && !allowedTypes.includes(files.type)) {
      createAlert(
        element,
        "only png, jpg format are support. please select image of these files format.",
        "alert"
      );
      return 0;
    }
    if (!multiple && files.size > 5000000) {
      createAlert(
        element,
        "Image size is greater then 5MB please select image less then 5MB.",
        "alert"
      );
      return 0;
    }
    const holder = document.getElementById("file-placeholder");

    const reader = new FileReader();
    reader.onload = (e) => {
      holder.innerHTML = `<img src='${e.target.result}' alt='picture'/>`;
    };
    if (multiple) {
      let i = 0;
      for (let img of files) {
        const reader = new FileReader();
        reader.onload = (e) => {
          if (i == 0)
            holder.innerHTML = `<img src='${e.target.result}' alt='picture'/>`;
          else
            holder.innerHTML += `<img src='${e.target.result}' alt='picture'/>`;
          i++;
        };
        reader.readAsDataURL(img);
      }
    } else {
      const reader = new FileReader();
      reader.onload = (e) => {
        holder.innerHTML = `<img src='${e.target.result}' alt='picture'/>`;
      };
      reader.readAsDataURL(files);
    }
  }
  // }
};
const handleImgPlaceholder = () =>
  document.getElementById("image_input").click();
const IntersectionOptions = {
  root: null,
  rootMargin: "-140px 0px -140px 0px",
  threshold: 0.45,
};
function viewPort(element) {
  let onView = element.attributes["data-onview"];
  let onOut = element.attributes["data-onout"];
  onView = onView ? onView.value : "fade-in";
  onOut = onOut ? onOut.value : "fade-out";
  const intersectionObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) element.classList.add(onView);
      else {
        element.classList.remove(onView);
        element.classList.add(onOut);
      }
    });
  }, IntersectionOptions);
  intersectionObserver.observe(element);
}
const lazyLoad = (element) => {
  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        element.src = element.dataset.src;
        element.classList.remove("lazy-load");
        observer.unobserve(element);
      }
    });
  }, {});
  observer.observe(element);
};
function determineWeatherConditions(conditionaly) {
  let temperature = 0;
  let windSpeed = 0;
  if (conditionaly) {
    temperature = Number(conditionaly.temp).toFixed(0);
    humidity = Number(conditionaly.humidity).toFixed(0);
    windSpeed = Number(conditionaly.wind).toFixed(0);
  } else {
    temperature = weather.current.temperature_2m;
    humidity = weather.current.relative_humidity_2m;
    windSpeed = weather.current.wind_speed_10m;
  }
  if (temperature > 25 && windSpeed < 15) {
    return "Sunny";
  }
  if (temperature > 20 && windSpeed < 20) {
    return "Partly Cloudy";
  }
  if (temperature > 15 && windSpeed > 20) {
    return "Cloudy";
  }
  return "Rainy";
}

const setWeatherImage = (image, conditionaly = false) => {
  if (image === undefined && conditionaly === false)
    return "/static/media/icons/sun.svg";
  let imageSrc = "/static/media/icons";
  const weatherConditions = determineWeatherConditions(conditionaly);
  let src = "";
  if (weatherConditions === "Sunny") {
    src = "/sun.png";
  } else if (weatherConditions === "Cloudy") {
    src = "/cloudy.png";
  } else if (weatherConditions === "Rainy") {
    src = "/rain.png";
  } else {
    src = "/clouds-and-sun.png";
  }

  if (conditionaly) return imageSrc + src;
  if (weatherConditions === "Sunny") image.classList.add("rotate");
  image.src = imageSrc + src;
};
const setupWeatherOnHeader = () => {
  const element = document.querySelectorAll(".header-weather");
  const image = document.querySelectorAll(".header-img");
  element.forEach((e) => (e.style.display = "flex"));
  document
    .querySelectorAll(".header-temp")
    .forEach((e) => (e.innerHTML = weather.current.temperature_2m.toFixed(0)));
  element[0].title =
    determineWeatherConditions(weather) + " Weather in Socotra";
  image.forEach((e) => setWeatherImage(e));
};
const setupWeatherOnPage = () => {
  const weatherTemp = document.querySelector("#weather-page .temp h3");
  weatherTemp.innerHTML = weather.current.temperature_2m.toFixed(0);
  setWeatherImage(document.querySelector("#page-weather-image"));
  document.querySelector("#weather-page .text .cond").innerHTML =
    determineWeatherConditions(weather);
  document.querySelector("#weather-page .text .wind").innerHTML =
    weather.current.wind_speed_10m;
  let humidity = findAverage(
    weather.hourly.relative_humidity_2m.slice(0, 24)
  ).toFixed(0);
  document.querySelector("#weather-page .text .hum").innerHTML = humidity;
};
// setup weather days here ...
const setupWeatherDays = () => {
  const section = document.getElementById("days");
  let w = 0;
  for (let i = 0; i < 7; i++) {
    let day = life(weather.hourly.time[w]).format("ddd");
    let temp = findAverage(
      weather.hourly.temperature_2m.slice(w, w + 24)
    ).toFixed(0);
    let humidity = findAverage(
      weather.hourly.relative_humidity_2m.slice(w, w + 24)
    ).toFixed(0);
    let wind_speed = findAverage(
      weather.hourly.wind_speed_10m.slice(w, w + 24)
    ).toFixed(0);
    let image = setWeatherImage(undefined, {
      timeIndex: w,
      temp,
      humidity,
      wind: wind_speed,
    });
    section.innerHTML += `
      <section onclick="setupWeatherHourly(${w})">
        <span>${day}</span>
        <img src="${image}"/>
        <span>
            ${temp}<span class="scx">°C</span>
        </span>
        <span>
          ${humidity}%
        </span>
      </section>
    `;
    w += 24;
  }
};
//setup hourly weather
const setupWeatherHourly = (startIndex) => {
  const time = weather.hourly.time.slice(startIndex, startIndex + 24);
  const temp = weather.hourly.temperature_2m.slice(startIndex, startIndex + 24);
  const hum = weather.hourly.relative_humidity_2m.slice(
    startIndex,
    startIndex + 24
  );
  const wind = weather.hourly.wind_speed_10m.slice(startIndex, startIndex + 24);
  document.querySelector("#weather-page .xkk").innerHTML = life(time[0]).format(
    "dddd"
  );
  const element = document.getElementById("hourly");
  element.innerHTML = "";
  time.forEach((t, i) => {
    let image = setWeatherImage(undefined, {
      timeIndex: i,
      temp: temp[i],
      hum: hum[i],
      wind: wind[i],
    });
    let sec = `
      <section style="transition: 300ms">
        <span class="time">${life(t).format("H")}</span>
        <img src="${image}" />
        <span>${temp[i]}°C</span>
        <span>${hum[i]}%</span>
        <span>${wind[i].toFixed(0)} km/h</span>
      </section> 
    `;
    element.innerHTML += sec;
  });
};
const setupWeather = () => {
  if (document.querySelector(".header-weather")) setupWeatherOnHeader();
  if (document.getElementById("weather-page")) setupWeatherOnPage();
  if (document.getElementById("days")) setupWeatherDays();
  if (document.getElementById("hourly")) setupWeatherHourly(0);
};
const getWeather = async () => {
  if (sessionStorage.getItem("weather")) {
    weather = JSON.parse(sessionStorage.getItem("weather"));
  } else {
    const url = "https://api.open-meteo.com/v1/forecast?latitude=12.6445&longitude=53.9601&current=temperature_2m,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m";
   //const url = "/static/js/test-weather.json";
    await fetch(url, { method: "GET" })
      .then((res) => res.json())
      .then((res) => {
        sessionStorage.setItem("weather", JSON.stringify(res));
        weather = res;
      })
      .catch((e) => console.error(e));
  }
  setupWeather(weather);
};
const imageRegistry = {};
const resSlideImage = (id, num) => {
  const images = document.querySelectorAll("#" + id + " img");
  images.forEach((e) => e.classList.remove("active"));
  if (imageRegistry[id]) {
    imageRegistry[id] =
      num === -1
        ? imageRegistry[id] >= 1
          ? imageRegistry[id] - 1
          : 0
        : imageRegistry[id] >= images.length - 1
        ? 0
        : imageRegistry[id] + 1;
  } else {
    imageRegistry[id] = num === -1 ? images.length - 1 : 1;
  }

  images[imageRegistry[id]].classList.add("active");
};
const deleteForm = async (table, id) => {
  await fetch(`/api/delete-data.php?deleteForm=1&id=${id}&table=${table}`)
    .then((res) => res.text())
    .then((res) => (document.location.href = window.location.href))
    .catch((e) => console.error(e));
};
const hotelResForm = (self) => {
  const form = document.getElementById("hotel-res");
  const fields = [
    "name",
    "email",
    "hotel",
    "check_in_date",
    "check_out_date",
    "guests",
  ];
  let allFieldsFilled = true;
  fields.forEach((field) => {
    const inputElement = form[field];
    if (inputElement.value.trim() === "") {
      createAlert(
        inputElement,
        `Please fill the ${field.replace("_", " ")} field`,
        "alert"
      );
      allFieldsFilled = false;
    }
  });
  if (allFieldsFilled) {
    const formData = new FormData(form);
    formData.append("hotel-res", 1);
    fetch("/api/set-data.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.success) {
          createAlert("alert-text", result.message, "success", (time = 100000));
        }
        if (form["form_id"].value === "") form["form_id"].value = result.id;
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }
};
window.addEventListener("DOMContentLoaded", async (e) => {
  await getWeather();
  // intersection observer on view
  const intersectionElement = document.querySelectorAll(".Observe");
  intersectionElement.forEach((element) => {
    viewPort(element);
  });
  // lazy loading images
  const lazyLoadImages = document.querySelectorAll(".lazy-load");
  lazyLoadImages.forEach((e) => lazyLoad(e));
  // adding and remvoing effect on header by scroll
  const header = document.getElementById("header");
  let scrollToNum = window.innerWidth > 380 ? 100 : 40;
  window.addEventListener("scroll", (e) => {
    if (scrollY > scrollToNum) {
      header.classList.add("bg");
    } else {
      header.classList.remove("bg");
    }
  });
});
