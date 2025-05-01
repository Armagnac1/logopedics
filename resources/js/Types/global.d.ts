import axios from 'axios';

declare global {
  interface Window {
    axios: typeof axios;
  }
}
declare module '*.vue' {
    import { DefineComponent } from 'vue'
    const component: DefineComponent<{}, {}, any>
    export default component
}
