import { Head } from "@inertiajs/react";
import MainLayout from "@/Layouts/MainLayout";
import LoginForm from "@/Components/Forms/LoginForm";
import "../../../css/admin/admin.css";

export default function AdminLogin() {
    return (
        <>
            <Head title="Admin Login" />
            <div className="container-fluid vh-100 admin-bg">
                <div className="row h-100 justify-content-center align-items-center">
                    <div className="col-sm-6 col-md-4 col-lg-3">
                        <div className="card-body">
                            <h2 className="text-center mb-4">
                                <img
                                    src="/assets/logo.svg"
                                    alt="TopIT Logo"
                                    width="150"
                                    height="50"
                                />
                            </h2>
                            <LoginForm routeName={'admin.login'} btn="btn-danger"/>
                        </div>

                        <div className="mt-5 text-center">
                            <a
                                href={route("login")}
                                className="text-light auth_btn btn"
                            >
                                Login as a Student
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
AdminLogin.layout = (page) => <MainLayout children={page}/>;