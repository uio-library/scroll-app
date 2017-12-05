
// For now we use sessinoStorage. Can switch to localStorage later if we want.
const storage = sessionStorage;

var PersistentState = {
  get: function(key, defaultValue) {
    let value = JSON.parse(storage.getItem(key, defaultValue));
    if (value === null) {
      value = defaultValue;
    }
    return value;
  },
  put: function(key, value) {
    storage.setItem(key,  JSON.stringify(value));
  },
}

export default PersistentState;
