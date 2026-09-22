import { RequireGuest } from "@/components/common/RequireGuest";
import { RegisterForm } from "@/components/organisms/RegisterForm";
import { AuthTemplate } from "@/components/templates/AuthTemplate";

export const metadata = {
  title: "Register · OnlyVitals",
};

export default function RegisterPage() {
  return (
    <RequireGuest>
      <AuthTemplate title="Register">
        <RegisterForm />
      </AuthTemplate>
    </RequireGuest>
  );
}
