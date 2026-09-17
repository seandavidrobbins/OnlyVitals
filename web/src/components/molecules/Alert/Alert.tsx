import type { ReactNode } from "react";

type AlertTone = "success" | "danger";

type AlertProps = {
  children: ReactNode;
  tone?: AlertTone;
};

const toneClassName: Record<AlertTone, string> = {
  success: "border-emerald-300 bg-emerald-50 text-emerald-900",
  danger: "border-red-300 bg-red-50 text-red-900",
};

export function Alert({ children, tone = "success" }: AlertProps) {
  return (
    <p
      role="status"
      className={`rounded-md border px-3 py-2 text-sm ${toneClassName[tone]}`}
    >
      {children}
    </p>
  );
}
