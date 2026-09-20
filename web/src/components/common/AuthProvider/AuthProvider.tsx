"use client";

import {
  createContext,
  useContext,
  useEffect,
  useMemo,
  useState,
  type ReactNode,
} from "react";

import {
  getMe,
  logoutRequest,
  type AuthResponse,
  type User,
} from "@/lib/api";
import { clearToken, getToken, persistToken } from "@/lib/token";

type AuthContextValue = {
  user: User | null;
  token: string | null;
  isReady: boolean;
  login: (auth: AuthResponse) => void;
  logout: () => Promise<void>;
};

const AuthContext = createContext<AuthContextValue | null>(null);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isReady, setIsReady] = useState(false);

  useEffect(() => {
    const storedToken = getToken();

    if (!storedToken) {
      setIsReady(true);
      return;
    }

    setToken(storedToken);

    getMe()
      .then((currentUser) => {
        setUser(currentUser);
      })
      .catch(() => {
        clearToken();
        setToken(null);
        setUser(null);
      })
      .finally(() => {
        setIsReady(true);
      });
  }, []);

  const value = useMemo<AuthContextValue>(
    () => ({
      user,
      token,
      isReady,
      login(auth) {
        persistToken(auth.token);
        setToken(auth.token);
        setUser(auth.user);
      },
      async logout() {
        try {
          await logoutRequest();
        } finally {
          clearToken();
          setToken(null);
          setUser(null);
        }
      },
    }),
    [user, token, isReady],
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth(): AuthContextValue {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error("useAuth must be used within AuthProvider");
  }

  return context;
}
