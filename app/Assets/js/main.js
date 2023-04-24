/**
 * Template Name: NiceAdmin
 * Updated: Mar 09 2023 with Bootstrap v5.2.3
 * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
(function () {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim();
    if (all) {
      return [...document.querySelectorAll(el)];
    } else {
      return document.querySelector(el);
    }
  };

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach((e) => e.addEventListener(type, listener));
    } else {
      select(el, all).addEventListener(type, listener);
    }
  };

  /**
   * Easy on scroll event listener
   */
  const onscroll = (el, listener) => {
    el.addEventListener("scroll", listener);
  };

  /**
   * Sidebar toggle
   */
  if (select(".toggle-sidebar-btn")) {
    on("click", ".toggle-sidebar-btn", function (e) {
      select("body").classList.toggle("toggle-sidebar");
    });
  }

  /**
   * Search bar toggle
   */
  if (select(".search-bar-toggle")) {
    on("click", ".search-bar-toggle", function (e) {
      select(".search-bar").classList.toggle("search-bar-show");
    });
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select("#navbar .scrollto", true);
  const navbarlinksActive = () => {
    let position = window.scrollY + 200;
    navbarlinks.forEach((navbarlink) => {
      if (!navbarlink.hash) return;
      let section = select(navbarlink.hash);
      if (!section) return;
      if (
        position >= section.offsetTop &&
        position <= section.offsetTop + section.offsetHeight
      ) {
        navbarlink.classList.add("active");
      } else {
        navbarlink.classList.remove("active");
      }
    });
  };
  window.addEventListener("load", navbarlinksActive);
  onscroll(document, navbarlinksActive);

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select("#header");
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add("header-scrolled");
      } else {
        selectHeader.classList.remove("header-scrolled");
      }
    };
    window.addEventListener("load", headerScrolled);
    onscroll(document, headerScrolled);
  }

  /**
   * Back to top button
   */
  let backtotop = select(".back-to-top");
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add("active");
      } else {
        backtotop.classList.remove("active");
      }
    };
    window.addEventListener("load", toggleBacktotop);
    onscroll(document, toggleBacktotop);
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  /**
   * Initiate quill editors
   */
  if (select(".quill-editor-default")) {
    new Quill(".quill-editor-default", {
      theme: "snow",
    });
  }

  if (select(".quill-editor-bubble")) {
    new Quill(".quill-editor-bubble", {
      theme: "bubble",
    });
  }

  if (select(".quill-editor-full")) {
    new Quill(".quill-editor-full", {
      modules: {
        toolbar: [
          [
            {
              font: [],
            },
            {
              size: [],
            },
          ],
          ["bold", "italic", "underline", "strike"],
          [
            {
              color: [],
            },
            {
              background: [],
            },
          ],
          [
            {
              script: "super",
            },
            {
              script: "sub",
            },
          ],
          [
            {
              list: "ordered",
            },
            {
              list: "bullet",
            },
            {
              indent: "-1",
            },
            {
              indent: "+1",
            },
          ],
          [
            "direction",
            {
              align: [],
            },
          ],
          ["link", "image", "video"],
          ["clean"],
        ],
      },
      theme: "snow",
    });
  }

  /**
   * Initiate TinyMCE Editor
   */
  const useDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
  const isSmallScreen = window.matchMedia("(max-width: 1023.5px)").matches;

  tinymce.init({
    selector: "textarea.tinymce-editor",
    plugins:
      "preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons",
    editimage_cors_hosts: ["picsum.photos"],
    menubar: "file edit view insert format tools table help",
    toolbar:
      "undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl",
    toolbar_sticky: true,
    toolbar_sticky_offset: isSmallScreen ? 102 : 108,
    autosave_ask_before_unload: true,
    autosave_interval: "30s",
    autosave_prefix: "{path}{query}-{id}-",
    autosave_restore_when_empty: false,
    autosave_retention: "2m",
    image_advtab: true,
    link_list: [
      {
        title: "My page 1",
        value: "https://www.tiny.cloud",
      },
      {
        title: "My page 2",
        value: "http://www.moxiecode.com",
      },
    ],
    image_list: [
      {
        title: "My page 1",
        value: "https://www.tiny.cloud",
      },
      {
        title: "My page 2",
        value: "http://www.moxiecode.com",
      },
    ],
    image_class_list: [
      {
        title: "None",
        value: "",
      },
      {
        title: "Some class",
        value: "class-name",
      },
    ],
    importcss_append: true,
    file_picker_callback: (callback, value, meta) => {
      /* Provide file and text for the link dialog */
      if (meta.filetype === "file") {
        callback("https://www.google.com/logos/google.jpg", {
          text: "My text",
        });
      }

      /* Provide image and alt text for the image dialog */
      if (meta.filetype === "image") {
        callback("https://www.google.com/logos/google.jpg", {
          alt: "My alt text",
        });
      }

      /* Provide alternative source and posted for the media dialog */
      if (meta.filetype === "media") {
        callback("movie.mp4", {
          source2: "alt.ogg",
          poster: "https://www.google.com/logos/google.jpg",
        });
      }
    },
    templates: [
      {
        title: "New Table",
        description: "creates a new table",
        content:
          '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>',
      },
      {
        title: "Starting my story",
        description: "A cure for writers block",
        content: "Once upon a time...",
      },
      {
        title: "New list with dates",
        description: "New List with dates",
        content:
          '<div class="mceTmpl"><span class="cdate">cdate</span><br><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>',
      },
    ],
    template_cdate_format: "[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]",
    template_mdate_format: "[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]",
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar:
      "bold italic | quicklink h2 h3 blockquote quickimage quicktable",
    noneditable_class: "mceNonEditable",
    toolbar_mode: "sliding",
    contextmenu: "link image table",
    skin: useDarkMode ? "oxide-dark" : "oxide",
    content_css: useDarkMode ? "dark" : "default",
    content_style:
      "body { font-family:Helvetica,Arial,sans-serif; font-size:16px }",
  });

  /**
   * Initiate Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll(".needs-validation");

  Array.prototype.slice.call(needsValidation).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });

  /**
   * Initiate Datatables
   */
  const datatables = select(".datatable", true);
  datatables.forEach((datatable) => {
    new simpleDatatables.DataTable(datatable);
  });

  /**
   * Autoresize echart charts
   */
  const mainContainer = select("#main");
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(function () {
        select(".echart", true).forEach((getEchart) => {
          echarts.getInstanceByDom(getEchart).resize();
        });
      }).observe(mainContainer);
    }, 200);
  }
})();

// get single order menu
const render = (array) => {
  const levelOneMenu = [];
  // getting main lists
  array.map((child) => {
    const menuArray = child?.order.split(",");
    child.submenus = [];
    // Parents
    if (menuArray.length == 1) {
      return levelOneMenu.push(child);
    }
    // getting dropowns
    if (menuArray.length === 2) {
      levelOneMenu.map((arr) => {
        if (menuArray[0] == arr.order.split(",")[0]) {
          return arr["submenus"].push(child);
        }
      });
    }
  });
  // Returning the main array
  return levelOneMenu;
};

// Main list item
const mainMenuIteration = (menus, baseUrl) => {
  return menus?.map((menu) => {
    // html for list item
    return `
    <li class="nav-item">
      <a href="" data-bs-toggle="collapse" data-bs-target="#one" class="nav-link">
          <i class="fa ${menu?.icon} small"></i>
          <span class="w-100 small">${menu.name}</span>
          <i class="fa fa-chevron-down small"></i>
      </a>
      ${
        menu?.submenus.length
          ? subMenuIteration(menu?.submenus, menu?.name, baseUrl)
          : ""
      }
    </li>
  `;
  });
};

// Submenu iteration
const subMenuIteration = (submenus, id, baseUrl) => {
  // looping over submenus
  const sub = submenus
    .map((menu) => {
      // Directly returning the HTML
      return ` 
        <li class="nav-item list-group-item">
            <a href="${baseUrl + menu?.url}">
                <i class="fa fa-list small"></i>
                <span class="small px-2">${menu?.name}</span>
            </a>
        </li>`;
    })
    // jouning the url to prevent unexpected commas
    .join("");
  // returning actual submenu
  return `
    <ul class="navbar-collapse collapse mt-2" data-bs-parent="#sidebar-nav" id="one">
      ${sub}
    </ul>
  `;
};

const sweetAlert = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-success ms-3",
    cancelButton: "btn btn-danger",
  },
  buttonsStyling: false,
  confirmButtonText: "Confrim",
  showCancelButton: true,
  cancelButtonText: "Cancel",
});

// Sweet alert trigger function
const alert = (
  title = "Are you sure",
  text = "This action cannot be reverted!",
  icon = "warning",
  error
) => {
  return sweetAlert.fire({
    title,
    text,
    icon,
    reverseButtons: true,
    Error,
  });
};

// check if a value is empty
const empty = (value) => {
  if (value === "" || value.trim() === "") return true;
  return false;
};

/**
 * @param {*} values -> object
 * @returns if validation fails ? this finction will return an object with failed vaidations :
 * if validation passes returns a validated object back to the function
 */
const validate = (values) => {
  let errorsArray = {};
  Object.entries(values).map((obj) => {
    if (empty(obj[1])) {
      errorsArray = {
        ...errorsArray,
        [obj[0]]: `${obj[0]} is required!`,
      };
    }
  });

  if (Object.keys(errorsArray).length < 1) return true;
  errorElement(errorsArray);
  return false;
};

/**
 * @param {*} validated
 * @returns boolean
 * @description Get the Error messages and append it to the DOM,
 * @note specify a span element with data-validation and data-error dataset attributes to work properly
 */
const errorElement = (validated) => {
  // selecting all error boxes
  document.querySelectorAll("[data-validation]").forEach(function (error) {
    error.textContent = null;
    const validationName = error.getAttribute("data-error");
    if (validated.hasOwnProperty(validationName)) {
      const uppercaseName =
        validated[validationName][0].toUpperCase() +
        validated[validationName].substring(1);
      error.textContent = uppercaseName;
    }
  });
};

// checking file Uploads
const hasFile = (request) => {
  if (!request[0].files[0]) return "";
  return "hasFile";
};

// auto check create roles
const findParentOfNode = (menu) => {
  const parent = document.querySelector(`[data-id=${menu}]`);
  return parent;
};

// auto check create roles
const findChildrenOfNode = (menu) => {
  const children = document.querySelectorAll(`[data-parent=${menu}]`);
  return children;
};
