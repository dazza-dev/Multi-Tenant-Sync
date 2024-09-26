/**
 * axios setup to use mock service
 */

import router from "@/router";
import axios from "axios";

const axiosServices = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true,
  withXSRFToken: true,
});

// interceptor for http
axiosServices.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      router.push({ name: "login" });
      return Promise.reject("Unauthorized");
    }
    // Handle other errors here
    return Promise.reject(
      (error.response && error.response.data) || "Wrong Services"
    );
  }
);

//
axiosServices.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default axiosServices;
