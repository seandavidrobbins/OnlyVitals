import { Heading } from "@/components/atoms/Heading";
import { RequireAuth } from "@/components/common/RequireAuth";
import { WebsiteForm } from "@/components/organisms/WebsiteForm";

export const metadata = {
  title: "Add website · OnlyVitals",
};

export default function NewWebsitePage() {
  return (
    <RequireAuth>
      <main className="mx-auto flex w-full max-w-lg flex-col gap-4 px-6 py-16">
        <Heading>Add website</Heading>
        <WebsiteForm />
      </main>
    </RequireAuth>
  );
}
