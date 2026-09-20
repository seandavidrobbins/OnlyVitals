import { forwardRef, type InputHTMLAttributes } from "react";

type InputProps = InputHTMLAttributes<HTMLInputElement> & {
  invalid?: boolean;
};

export const Input = forwardRef<HTMLInputElement, InputProps>(
  function Input({ invalid = false, className = "", ...props }, ref) {
    return (
      <input
        ref={ref}
        aria-invalid={invalid || undefined}
        className={`w-full rounded-md border bg-background px-3 py-2 text-sm text-foreground outline-none ring-foreground/20 focus:ring-2 ${
          invalid ? "border-red-400" : "border-zinc-300 dark:border-zinc-600"
        } ${className}`}
        {...props}
      />
    );
  },
);
