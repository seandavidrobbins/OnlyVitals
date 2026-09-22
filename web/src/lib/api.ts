import { getToken } from "@/lib/token";

export type HealthResponse = {
  status: "ok";
};

export type User = {
  id: number;
  name: string;
  email: string;
};

export type AuthResponse = {
  user: User;
  token: string;
};

export type Website = {
  id: number;
  name: string;
  url: string;
  cms_type: "wordpress" | "other";
  notes: string | null;
  created_at: string;
  updated_at: string;
};

export class ApiValidationError extends Error {
  constructor(public readonly errors: Record<string, string[]>) {
    super("The given data was invalid.");
    this.name = "ApiValidationError";
  }

  first(field: string): string | undefined {
    return this.errors[field]?.[0];
  }
}

function getApiBaseUrl(): string {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL;

  if (!baseUrl) {
    throw new Error("NEXT_PUBLIC_API_URL is not set");
  }

  return baseUrl.replace(/\/$/, "");
}

function requestHeaders(): HeadersInit {
  const headers: Record<string, string> = {
    Accept: "application/json",
    "Content-Type": "application/json",
  };

  const token = getToken();

  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }

  return headers;
}

export async function getHealth(): Promise<HealthResponse> {
  const response = await fetch(`${getApiBaseUrl()}/health`, {
    headers: requestHeaders(),
    cache: "no-store",
  });

  if (!response.ok) {
    throw new Error(`Health check failed with status ${response.status}`);
  }

  const data: unknown = await response.json();

  if (
    typeof data !== "object" ||
    data === null ||
    !("status" in data) ||
    data.status !== "ok"
  ) {
    throw new Error("Health check returned an unexpected payload");
  }

  return data as HealthResponse;
}

function extractValidationErrors(data: unknown): Record<string, string[]> {
  if (
    typeof data !== "object" ||
    data === null ||
    !("errors" in data) ||
    typeof data.errors !== "object" ||
    data.errors === null
  ) {
    return {};
  }

  const errors: Record<string, string[]> = {};

  for (const [field, messages] of Object.entries(data.errors)) {
    if (
      Array.isArray(messages) &&
      messages.every((message) => typeof message === "string")
    ) {
      errors[field] = messages;
    }
  }

  return errors;
}

function isUser(data: unknown): data is User {
  if (typeof data !== "object" || data === null) {
    return false;
  }

  return (
    "id" in data &&
    typeof data.id === "number" &&
    "name" in data &&
    typeof data.name === "string" &&
    "email" in data &&
    typeof data.email === "string"
  );
}

function isAuthResponse(data: unknown): data is AuthResponse {
  if (typeof data !== "object" || data === null) {
    return false;
  }

  if (!("token" in data) || typeof data.token !== "string") {
    return false;
  }

  if (!("user" in data) || typeof data.user !== "object" || data.user === null) {
    return false;
  }

  return isUser(data.user);
}

async function postAuth(path: string, body: unknown): Promise<AuthResponse> {
  const response = await fetch(`${getApiBaseUrl()}${path}`, {
    method: "POST",
    headers: requestHeaders(),
    body: JSON.stringify(body),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (response.status === 422) {
    throw new ApiValidationError(extractValidationErrors(data));
  }

  if (!response.ok || !isAuthResponse(data)) {
    throw new Error(`Request to ${path} failed with status ${response.status}`);
  }

  return data;
}

export function register(payload: {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}): Promise<AuthResponse> {
  return postAuth("/register", payload);
}

export function login(payload: {
  email: string;
  password: string;
}): Promise<AuthResponse> {
  return postAuth("/login", payload);
}

export async function getMe(): Promise<User> {
  const response = await fetch(`${getApiBaseUrl()}/me`, {
    headers: requestHeaders(),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (
    !response.ok ||
    typeof data !== "object" ||
    data === null ||
    !("user" in data) ||
    !isUser(data.user)
  ) {
    throw new Error(`Current user request failed with status ${response.status}`);
  }

  return data.user;
}

function isWebsite(data: unknown): data is Website {
  if (typeof data !== "object" || data === null) {
    return false;
  }

  return (
    "id" in data &&
    typeof data.id === "number" &&
    "name" in data &&
    typeof data.name === "string" &&
    "url" in data &&
    typeof data.url === "string" &&
    "cms_type" in data &&
    (data.cms_type === "wordpress" || data.cms_type === "other") &&
    "notes" in data &&
    (typeof data.notes === "string" || data.notes === null) &&
    "created_at" in data &&
    typeof data.created_at === "string" &&
    "updated_at" in data &&
    typeof data.updated_at === "string"
  );
}

export type WebsitePayload = {
  name: string;
  url: string;
  cms_type: Website["cms_type"];
  notes: string | null;
};

function parseWebsiteResponse(data: unknown, path: string, status: number): Website {
  if (
    typeof data !== "object" ||
    data === null ||
    !("website" in data) ||
    !isWebsite(data.website)
  ) {
    throw new Error(`Request to ${path} failed with status ${status}`);
  }

  return data.website;
}

export async function getWebsites(): Promise<Website[]> {
  const response = await fetch(`${getApiBaseUrl()}/websites`, {
    headers: requestHeaders(),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (
    !response.ok ||
    typeof data !== "object" ||
    data === null ||
    !("websites" in data) ||
    !Array.isArray(data.websites) ||
    !data.websites.every(isWebsite)
  ) {
    throw new Error(`Websites request failed with status ${response.status}`);
  }

  return data.websites;
}

export async function getWebsite(id: number): Promise<Website> {
  const path = `/websites/${id}`;
  const response = await fetch(`${getApiBaseUrl()}${path}`, {
    headers: requestHeaders(),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (!response.ok) {
    throw new Error(`Website request failed with status ${response.status}`);
  }

  return parseWebsiteResponse(data, path, response.status);
}

export async function createWebsite(payload: WebsitePayload): Promise<Website> {
  const response = await fetch(`${getApiBaseUrl()}/websites`, {
    method: "POST",
    headers: requestHeaders(),
    body: JSON.stringify(payload),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (response.status === 422) {
    throw new ApiValidationError(extractValidationErrors(data));
  }

  if (!response.ok) {
    throw new Error(`Create website failed with status ${response.status}`);
  }

  return parseWebsiteResponse(data, "/websites", response.status);
}

export async function updateWebsite(
  id: number,
  payload: WebsitePayload,
): Promise<Website> {
  const path = `/websites/${id}`;
  const response = await fetch(`${getApiBaseUrl()}${path}`, {
    method: "PUT",
    headers: requestHeaders(),
    body: JSON.stringify(payload),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (response.status === 422) {
    throw new ApiValidationError(extractValidationErrors(data));
  }

  if (!response.ok) {
    throw new Error(`Update website failed with status ${response.status}`);
  }

  return parseWebsiteResponse(data, path, response.status);
}

export async function logoutRequest(): Promise<void> {
  const response = await fetch(`${getApiBaseUrl()}/logout`, {
    method: "POST",
    headers: requestHeaders(),
    cache: "no-store",
  });

  if (!response.ok && response.status !== 401) {
    throw new Error(`Logout failed with status ${response.status}`);
  }
}
