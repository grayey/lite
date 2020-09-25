
import moment from "moment";

export const formatDate = (value) => {
    if (value) {
    return moment(String(value)).format('MMM DD, YYYY')
  }
}
