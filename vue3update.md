# Vue 3 upgrade
## What's changed

### Reactivity
All the variables we used to put in `data()` will now be reactive constants. You cannot modify the variable directly, only modify its value by calling `variable.value`. These variables will automatically be unwrapped in the templates, so nothing will need to be changed there (mostly), however within the script, if you wish to refer to the value of the variable, you have to call `variable.value`.
When data changes in a reactive variable, all data that refers to the value of that variable will also be notified and changed if applicable.

### Store
Bye bye Vuex, hello dumb-ass pineapple. The biggest changes (regardless of the switch to Pinia) are as followed:
- Rather than using `.then()`, we now use `async function` with `await`. As such, calls are no longer wrapped in a promise, you can just await them.
- You need to instantiate the store you wish to use within the component. `import {useNamedStore} from '/js/store/nameStore';` followed by `const nameStore = useNameStore();`, where 'name' is replaced by whatever name the store uses.
- No standard getters, instead you can call upon the state directly. DO NOT mutate the state directly, I will judge you harshly. Get the state into a local variable using `const variableName = computed(() => nameStore.stateName);`. These cannot be muted. If you wish to change, either use an action (mainly for store changes) or copy into a local editable variable.
- Reduced amount of stores, `bugReportStore` has been mostly merged into `adminStore`, `notificationStore` has been mostly merged into `admin/messageStore`, `taskListStore` into `taskStore`. 

### Composition API
Switched from Options to Composition API. You will need to import several methods, such as `ref`, `computed` and `onMounted`. I have no idea what I'm meant to say about this. Check documentation.

### Translations
Most translations work the same way, however if you wish to use translations in the script, you will need to import the translator. Before you could use `this.$t()`, now you import `import {useI18n} from 'vue-i18n'` and instantiate the translator as: `const {t} = useI18n()`. Everything else works fine, except maybe the pluralisation, look at the documentation.

### No Bootstrap
Yay. I've made custom elements of the Modal, Table, a Dropdown, Progressbar and Tooltip. The Modal and Tooltip are globally mounted so you don't need to import those. Might globally import more components later.

### Upcoming stuff
There's a bunch of bugs that need fixing, they have priority. 
- Reduce store usage for component wide data #327
- Turn on Volar and fix the errors
Besides that, business as usual, look at the roadmap if you want to pick a direction to go. There are plenty of user stories in wait.