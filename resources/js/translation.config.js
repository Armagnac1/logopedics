import dayjs from "dayjs";
import localizedFormat from 'dayjs/plugin/localizedFormat.js';
import ru from "dayjs/locale/ru"

dayjs.extend(localizedFormat)
dayjs.locale('ru')

export { dayjs };
