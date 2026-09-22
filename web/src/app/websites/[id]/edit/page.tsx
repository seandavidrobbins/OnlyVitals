import { Heading } from "@/components/atoms/Heading";
import { RequireAuth } from "@/components/common/RequireAuth";
import { Alert } from "@/components/molecules/Alert";

import { EditWebsite } from "./EditWebsite";

export const metadata = {
  title: "Edit website · OnlyVitals",
};

type EditWebsitePageProps = {
  params: Promise<{ id: string }>;
};

export default async function EditWebsitePage({ params }: EditWebsitePageProps) {
  const { id } = await params;
  const websiteId = /^\d+$/.test(id) ? Number(id) : Number.NaN;

  return (
    <RequireAuth>
      <main className="mx-auto flex w-full max-w-lg flex-col gap-4 px-6 py-16">
        <Heading>Edit website</Heading>
        {Number.isInteger(websiteId) && websiteId > 0 ? (
          <EditWebsite id={websiteId} />
        ) : (
          <Alert tone="danger">Website not found.</Alert>
        )}
      </main>
    </RequireAuth>
  );
}
