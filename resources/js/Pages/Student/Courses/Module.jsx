import "../../../../css/module.css";
import { useRequest } from "@/Library/hooks";
import { dissectContent } from "@/Library/utils";
import LessonContent from "@/Components/Content/LessonContent";
import ModuleContent from "@/Components/Content/ModuleContent";
import { StudentContent } from "@/Components/LayoutContent/StudentContent";
import { useEffect } from "react";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function Module({ module }) {
    const { isProcessing, getRequest } = useRequest();

    useEffect(() => {
        if (module?.data?.lessons) {
            module.data.lessons.forEach((lesson, index) => {
                // Set up a ScrollTrigger for each lesson section
                ScrollTrigger.create({
                    trigger: `#lesson-${index}`,
                    start: "top center",
                    end: "bottom center",
                    // markers: true,
                    onEnter: () => highlightNavItem(index),
                    onEnterBack: () => highlightNavItem(index),
                });
            });
        }
        return () => {
            ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
        };
    }, [module]);

    const handleBackClick = async () => {
        getRequest("course.show", module.data.course.course_id);
    };

    const highlightNavItem = (index) => {
        // Remove active class from all navigation items
        const navItems = document.querySelectorAll(".sticky-nav ul li a");
        navItems.forEach((item) => item.classList.remove("active"));

        // Add active class to the current lesson's navigation link
        const activeItem = navItems[index];
        if (activeItem) activeItem.classList.add("active");
    };

    return (
        <>
            <div className="container-fluid p-5">
                <div className="row" id="module-title">
                    <div className="btn-toolbar mx-3 mb-3">
                        <button
                            className="btn btn-transparent"
                            disabled={isProcessing}
                            onClick={handleBackClick}
                        >
                            <i className="bi bi-arrow-left"></i> Back
                        </button>
                        {/* <h5 className="fw-regular mb-0 align-content-center">
                            Module Detail
                        </h5> */}
                    </div>
                    <div className="col-md-12 col-lg-10 px-5 pt-3">
                        <div className="row mb-2">
                            <h2 className="fw-bolder mb-3">
                                {module.data.title}
                            </h2>
                            <div id="header">
                                {module.data.content ? (
                                    <ModuleContent
                                        content={dissectContent(
                                            module.data.content
                                        )}
                                    />
                                ) : (
                                    <p></p>
                                )}
                            </div>
                        </div>
                        {/* <hr /> */}
                        {module.data.lessons && (
                            <div className="row">
                                <LessonContent data={module.data.lessons} />
                            </div>
                        )}
                    </div>
                    <div className="col-2 sticky-nav">
                        <a href="#module-title w-100">
                                {module.data.title}
                        </a>
                        <ul>
                            {module.data.lessons &&
                                module.data.lessons.map((lesson, index) => (
                                    <li key={index}>
                                        <a href={`#lesson-${index}`}>
                                            {lesson.title}
                                        </a>
                                    </li>
                                ))}
                        </ul>
                    </div>
                </div>
            </div>
        </>
    );
}

export default StudentContent(Module);
