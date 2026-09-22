"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { type FormEvent, useState } from "react";

import { Button } from "@/components/atoms/Button";
import { Label } from "@/components/atoms/Label";
import { FormField } from "@/components/molecules/FormField";
import {
  ApiValidationError,
  createWebsite,
  updateWebsite,
  type Website,
  type WebsitePayload,
} from "@/lib/api";

type WebsiteFormErrors = {
  name?: string;
  url?: string;
  cms_type?: string;
  notes?: string;
};

type WebsiteFormProps = {
  website?: Website;
};

const fieldClassName =
  "w-full rounded-md border border-zinc-300 bg-background px-3 py-2 text-sm text-foreground outline-none ring-foreground/20 focus:ring-2 dark:border-zinc-600";

export function WebsiteForm({ website }: WebsiteFormProps) {
  const router = useRouter();
  const isEdit = website !== undefined;
  const [name, setName] = useState(website?.name ?? "");
  const [url, setUrl] = useState(website?.url ?? "");
  const [cmsType, setCmsType] = useState<Website["cms_type"]>(
    website?.cms_type ?? "wordpress",
  );
  const [notes, setNotes] = useState(website?.notes ?? "");
  const [errors, setErrors] = useState<WebsiteFormErrors>({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setIsSubmitting(true);
    setErrors({});

    const payload: WebsitePayload = {
      name,
      url,
      cms_type: cmsType,
      notes: notes.trim() === "" ? null : notes,
    };

    try {
      if (isEdit) {
        await updateWebsite(website.id, payload);
      } else {
        await createWebsite(payload);
      }

      router.push("/dashboard");
    } catch (error) {
      if (error instanceof ApiValidationError) {
        setErrors({
          name: error.first("name"),
          url: error.first("url"),
          cms_type: error.first("cms_type"),
          notes: error.first("notes"),
        });
        return;
      }

      setErrors({
        name: isEdit
          ? "Unable to update website. Try again."
          : "Unable to add website. Try again.",
      });
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <form onSubmit={handleSubmit} className="flex flex-col gap-4">
      <FormField
        id="name"
        label="Name"
        type="text"
        name="name"
        value={name}
        onChange={(event) => setName(event.target.value)}
        error={errors.name}
        required
      />
      <FormField
        id="url"
        label="URL"
        type="url"
        name="url"
        value={url}
        onChange={(event) => setUrl(event.target.value)}
        error={errors.url}
        placeholder="https://example.com"
        required
      />
      <div className="flex flex-col gap-1.5">
        <Label htmlFor="cms_type">CMS</Label>
        <select
          id="cms_type"
          name="cms_type"
          value={cmsType}
          onChange={(event) =>
            setCmsType(event.target.value as Website["cms_type"])
          }
          className={fieldClassName}
          aria-invalid={Boolean(errors.cms_type) || undefined}
          aria-describedby={errors.cms_type ? "cms_type-error" : undefined}
          required
        >
          <option value="wordpress">WordPress</option>
          <option value="other">Other</option>
        </select>
        {errors.cms_type ? (
          <p id="cms_type-error" role="alert" className="text-sm text-red-700">
            {errors.cms_type}
          </p>
        ) : null}
      </div>
      <div className="flex flex-col gap-1.5">
        <Label htmlFor="notes">Notes</Label>
        <textarea
          id="notes"
          name="notes"
          value={notes}
          onChange={(event) => setNotes(event.target.value)}
          rows={4}
          className={fieldClassName}
          aria-invalid={Boolean(errors.notes) || undefined}
          aria-describedby={errors.notes ? "notes-error" : undefined}
        />
        {errors.notes ? (
          <p id="notes-error" role="alert" className="text-sm text-red-700">
            {errors.notes}
          </p>
        ) : null}
      </div>
      <Button type="submit" disabled={isSubmitting}>
        {isSubmitting
          ? isEdit
            ? "Saving…"
            : "Adding…"
          : isEdit
            ? "Save website"
            : "Add website"}
      </Button>
      <p className="text-sm text-zinc-600 dark:text-zinc-400">
        <Link href="/dashboard" className="font-medium text-foreground underline">
          Back to dashboard
        </Link>
      </p>
    </form>
  );
}
