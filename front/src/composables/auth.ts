import { ref } from 'vue';

const isAuthenticated = ref(!!localStorage.getItem('authToken'));

const setAuth = (token: string, user: any) => {
  localStorage.setItem('authToken', token);
  localStorage.setItem('user', JSON.stringify(user));

  isAuthenticated.value = true;
};

const clearAuth = () => {
  localStorage.removeItem('authToken');
  localStorage.removeItem('user');
  
  isAuthenticated.value = false;
};

export function useAuth() {
  return {
    isAuthenticated,
    setAuth,
    clearAuth,
  };
}