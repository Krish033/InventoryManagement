/**
 * Latest swal function doesnot fit the requirements
 * @param {*} obj
 * @param {*} callback
 */
const swal = async (obj, callback) => {
  const { isConfirmed, isDenied } = await Swal.fire({
    ...obj,
  });

  if (isConfirmed) {
    callback();
  }
};

/**
 * Getting the first element from an array
 * @param {*} array
 * @returns ```array``` first element of an array
 */
const first = (array) => {
  return array[0];
};

/**
 * unwanted
 * @param  {...any} main
 */
const ajaxindicatorstart = (...main) => {
  console.log("Started");
};

/**
 * unwanted
 * @param  {...any} main
 */
const ajaxindicatorstop = (...main) => {
  console.log("Stopped");
};

/**
 * find if an item is null
 * @param {*} data
 * @returns
 */
const isNull = (data) => {
  return data === null;
};

/**
 * ---------------------------------------------
 * ## Floats@feelingPowerful
 * ---------------------------------------------
 * Handle Floats and decimals, on your own way
 */
class Float {
  /**
   * Strip decimals
   * @param {*} float
   * @param {*} len
   * @param {*} separator
   * @returns
   */
  static decimals = (float, len = 2, separator = ".") => {
    const splitted = new String(float).split(separator);

    if (!splitted[1]) return float;

    splitted[1] = splitted[1].substring(0, len);

    const main = splitted.join(separator);
    return parseFloat(main);
  };
}

/**
 * ---------------------------------------------
 * ## Arrays@feelingPowerful
 * ---------------------------------------------
 * Working with object denyList
 */
class Arr {
  // getting the object to work with
  static object(obj) {
    this.requestObject = obj;
    return this;
  }

  /**
   * create an object without denyList
   * @param {*} array
   * @returns
   */
  static except(array = []) {
    this.subObj = {};
    // lowercasing the array
    array = [...array].map((item) => item.toLocaleLowerCase());
    // chcking if the method is not called on empty object
    if (!this.requestObject)
      throw new ReferenceError("Cannot create object on empty requests");
    // adding item to array
    Object.entries(this.requestObject).map((item) => {
      if (!array.includes(item[0].toLocaleLowerCase())) {
        this.subObj = { ...this.subObj, [item[0]]: item[1] };
      }
    });

    return this;
  }

  static only(array = []) {
    this.subObj = {};
    // lowercasing the array
    array = [...array].map((item) => item.toLocaleLowerCase());
    // chcking if the method is not called on empty object
    if (!this.requestObject)
      throw new ReferenceError("Cannot create object on empty requests");
    // adding item to array
    Object.entries(this.requestObject).map((item) => {
      if (array.includes(item[0].toLocaleLowerCase())) {
        this.subObj = { ...this.subObj, [item[0]]: item[1] };
      }
    });
  }

  /**
   * return the requested value
   * @returns mainData
   */
  static get() {
    return this.subObj;
  }
}

/**
 * ---------------------------------------------
 * ## Appends@feelingPowerful
 * ---------------------------------------------
 * Appending items based on id
 */
class Append {
  constructor(parent) {
    this.parent = parent;
  }
  /**
   * Getting the ids to append
   * @param {*} obj
   * @returns
   */
  ids(obj) {
    this.ids = obj;
    return this;
  }

  /**
   * Includes selected to add to values
   * @returns this
   */
  includesSelect() {
    this.selects = true;
    return this;
  }

  /**
   * Append values to DOM
   */
  create() {
    Object.entries(this.ids).map((item) => {
      const element = this.findElementById(item[0]);

      if (element) {
        element.value = item[1];
      }

      if (this.selects && element instanceof HTMLSelectElement) {
        element.value == item[1];
        element.selected = item[1].toLocaleLowerCase();
        console.log(element.value);
        // element.selected = element.value == item[1];
      }
    });

    return true;
  }

  findElementById(item) {
    const el = [
      ...this.parent.querySelectorAll("input"),
      ...this.parent.querySelectorAll("select"),
    ];

    if (!this.parent) {
      throw new ReferenceError("Parent should be configured");
    }

    return (
      first([...el.filter((e) => e.id.toLocaleLowerCase() === item)]) || false
    );
  }

  /**
   * Should also be working with datasets
   * Easily work with datasets
   * @returns this
   */
  dataset() {
    this.datasets = this.parent.dataset;
    return this;
  }

  /**
   * Add to elements dataset
   * @param {*} object
   * @returns
   */
  add(object) {
    if (!this.datasets) {
      throw new ReferenceError("Cannot add to Empty Dataset");
    }
    // adding items to dataset
    Object.entries(object).map((item) => {
      this.parent.setAttribute(item[0], item[1]);
    });

    return true;
  }

  update(object) {
    if (!this.datasets) {
      throw new ReferenceError("Cannot add to Empty Dataset");
    }
    const appendedList = [];
    // adding items to dataset
    Object.entries(this.datasets).map((item) => {
      if (this.parent.dataset.hasOwnProperty(item[0].toLocaleLowerCase())) {
        this.parent.setAttribute(item[0], item[1]);
        appendedList.push(item[0]);
      }
    });
    // returning the items pushed
    return appendedList;
  }

  /**
   * clear item of parent dataset
   * @param {*} array
   */
  clear(array) {
    if (!this.datasets) {
      throw new ReferenceError("Cannot add to Empty Dataset");
    }

    // adding items to dataset
    Object.entries(this.datasets).map((item) => {
      if (array.includes(item[0])) {
        this.parent.setAttribute(item[0], "");
        appendedList.push(item[0]);
      }
    });
    // returning the items pushed
    return appendedList;
  }

  /**
   * Get items from dataset
   * @param {*} array
   * @returns
   */
  get(array = []) {
    if (!this.dataset) {
      throw new ReferenceError("Cannot add to Empty Dataset");
    }
    // if not asked for specific properties
    if (!array.lenght) {
      return this.datasets;
    }
    // return speciic properties
    return Arr.object(this.datasets).only([...array]);
  }
}

const createAppend = (parent) => {
  return new Append(parent);
};
