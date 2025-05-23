import "../../../css/student/dashboard.css";
import { useEffect } from "react";
import TestHistory from "@/Components/Test/TestHistoryList";
import { Link } from "@inertiajs/react";
import { StudentContent } from "@/Components/LayoutContent/StudentContent";
import ThetaScoreLine from "@/Components/Chart/ThetaScoreLine";
import AbilityEstimateCards from "@/Components/Student/AbilityEstimateCards";

function Dashboard({ thetaScore, tests, courseCards }) {
    const testsData = tests && tests.data ? tests.data : [];

    return (
        <>
            <div className="row p-3">
                <div className="row mt-4 px-5">
                    <h3 className="fw-bold mb-4">Dashboard</h3>

                    <AbilityEstimateCards courses={courseCards}/>

                    <h5 className="fw-semibold">Ability Scores Chart</h5>
                    <div className="row w-100 px-5 mb-3">
                        <div className="chart-container">
                            <ThetaScoreLine thetaScoreData={thetaScore} />
                        </div>
                    </div>
                </div>
                <div className="row mt-4 px-5">
                    <div className="d-flex justify-content-between align-items-center mb-3">
                        <h5 className="fw-semibold fs-5">Recent Attempts</h5>
                        <Link
                            href={`/test/history`}
                            className="text-decoration-none"
                        >
                            View Test History
                        </Link>
                    </div>
                    <TestHistory tests={testsData} />
                </div>
            </div>
        </>
    );
}

export default StudentContent(Dashboard);
