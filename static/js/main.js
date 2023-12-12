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
    if(form[f].value.trim() === ''){
      let name = f === 'dob' ? 'date of birth' : f.replace('_', ' ');
      createAlert(form[f], `Please fill ${name} field. ${name} is required.`, 'alert');
      isValid = false;
      break;
    }else{
      formData.append(f, form[f].value);
    }
  }
  if(isValid){
   formData.append('save-visa', 1); 
   if(form['form-type'].value === 'update') formData.append('form-id', form['form-id'].value);
   formData.append('form-type', form['form-type'].value)
    await fetch('/api/set-data.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(res => {
      console.log(res)
      if(res === 'success'){
        createAlert("alert-text", form['form-type'].value === 'update' ? "Updated successfully!" : "Your form is submitted successfully. we will inform you in next step", 'success');
      }
     } )
    .catch(e => createAlert('alert-text', e.message, 'danger'));

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
        window.location.href = "/";
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
  console.log(form);
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
            id: Number(res.id)
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
const createAlert = (id, message, variant) => {
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
  }, 5000);
  return 1;
};
const handleImage = (element) => {
  if (element.files && element.files[0]) {
    const file = element.files[0];
    let allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
    if (!allowedTypes.includes(file.type)) {
      createAlert(
        element,
        "only png, jpg format are support. please select image of these files format.",
        "alert"
      );
      return 0;
    }
    if (file.size > 5000000) {
      createAlert(
        element,
        "Image size is greater then 5MB please select image less then 5MB.",
        "alert"
      );
      return 0;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
      document.getElementById(
        "file-placeholder"
      ).innerHTML = `<img src='${e.target.result}' alt='picture'/>`;
    };
    reader.readAsDataURL(file);
  }
};
const handleImgPlaceholder = () => document.getElementById("avatar").click();
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
window.addEventListener("DOMContentLoaded", (e) => {
  // intersection observer on view
  const intersectionElement = document.querySelectorAll(".Observe");
  intersectionElement.forEach((element) =>{return 0; viewPort(element)});
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
console.log("js is connected")