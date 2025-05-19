import React, { useState, useEffect } from 'react';
import { Head } from '@inertiajs/react';
import {
    PieChart, Pie, BarChart, Bar, LineChart, Line,
    XAxis, YAxis, CartesianGrid, Tooltip, Legend,
    ResponsiveContainer, Cell
} from 'recharts';

const AssessmentReportDashboard = ({ registration, assessmentData, locale = 'en' }) => {
    const [currentLocale, setCurrentLocale] = useState(locale);
    const [loading, setLoading] = useState(true);
    const [stats, setStats] = useState({
        completionRate: 0,
        totalAnswers: 0,
        yesAnswers: 0,
        noAnswers: 0,
        naAnswers: 0,
        categoryScores: [],
        progressOverTime: []
    });

    // Toggle language
    const toggleLanguage = () => {
        setCurrentLocale(prevLocale => (prevLocale === 'en' ? 'ar' : 'en'));
    };

    // Calculate statistics from assessment data
    useEffect(() => {
        if (assessmentData) {
            setLoading(true);

            try {
                // Process the assessment data to generate statistics
                const responses = assessmentData;
                const responseValues = Object.values(responses);

                // Count response types
                let yesCount = 0;
                let noCount = 0;
                let naCount = 0;

                responseValues.forEach(response => {
                    if (response.response === 'yes') yesCount++;
                    else if (response.response === 'no') noCount++;
                    else if (response.response === 'na') naCount++;
                });

                const totalResponses = yesCount + noCount + naCount;

                // Calculate completion rate (assuming total questions is known or can be derived)
                // For demonstration, we'll use totalResponses as the denominator
                const completionRate = totalResponses > 0 ?
                    Math.round((totalResponses / (totalResponses + 5)) * 100) : 0; // +5 is just for demo

                // Example category scores (in a real app, you would calculate this from your data)
                const categoryScores = [
                    { name: 'Category 1', score: Math.round(Math.random() * 30 + 65) },
                    { name: 'Category 2', score: Math.round(Math.random() * 30 + 65) },
                    { name: 'Category 3', score: Math.round(Math.random() * 30 + 65) },
                    { name: 'Category 4', score: Math.round(Math.random() * 30 + 65) },
                    { name: 'Category 5', score: Math.round(Math.random() * 30 + 65) }
                ];

                // Example progress over time (in a real app, you would get this from your backend)
                const progressOverTime = [
                    { month: 'Jan', progress: Math.round(completionRate * 0.2) },
                    { month: 'Feb', progress: Math.round(completionRate * 0.4) },
                    { month: 'Mar', progress: Math.round(completionRate * 0.6) },
                    { month: 'Apr', progress: Math.round(completionRate * 0.8) },
                    { month: 'May', progress: completionRate }
                ];

                setStats({
                    completionRate,
                    totalAnswers: totalResponses,
                    yesAnswers: yesCount,
                    noAnswers: noCount,
                    naAnswers: naCount,
                    categoryScores,
                    progressOverTime
                });

                setLoading(false);
            } catch (error) {
                console.error("Error processing assessment data:", error);
                setLoading(false);
            }
        } else {
            // If no data is available yet, use dummy data
            setTimeout(() => {
                const dummyStats = {
                    completionRate: 75,
                    totalAnswers: 30,
                    yesAnswers: 22,
                    noAnswers: 5,
                    naAnswers: 3,
                    categoryScores: [
                        { name: 'Category 1', score: 85 },
                        { name: 'Category 2', score: 72 },
                        { name: 'Category 3', score: 68 },
                        { name: 'Category 4', score: 91 },
                        { name: 'Category 5', score: 77 }
                    ],
                    progressOverTime: [
                        { month: 'Jan', progress: 15 },
                        { month: 'Feb', progress: 30 },
                        { month: 'Mar', progress: 45 },
                        { month: 'Apr', progress: 60 },
                        { month: 'May', progress: 75 }
                    ]
                };

                setStats(dummyStats);
                setLoading(false);
            }, 1000);
        }
    }, [assessmentData]);

    // Translations
    const translations = {
        en: {
            assessmentResults: "Assessment Results",
            overview: "Overview",
            statistics: "Statistics",
            categoryPerformance: "Category Performance",
            progressOverTime: "Progress Over Time",
            completionRate: "Completion Rate",
            responseSummary: "Response Summary",
            yes: "Yes",
            no: "No",
            notApplicable: "Not Applicable",
            backToAssessment: "Back to Assessment",
            loading: "Loading results...",
            noDataAvailable: "No data available",
            score: "Score",
            progress: "Progress",
            month: "Month",
            category: "Category"
        },
        ar: {
            assessmentResults: "نتائج التقييم",
            overview: "نظرة عامة",
            statistics: "إحصائيات",
            categoryPerformance: "أداء الفئة",
            progressOverTime: "التقدم مع مرور الوقت",
            completionRate: "معدل الإكمال",
            responseSummary: "ملخص الاستجابة",
            yes: "نعم",
            no: "لا",
            notApplicable: "لا ينطبق",
            backToAssessment: "العودة للتقييم",
            loading: "جاري تحميل النتائج...",
            noDataAvailable: "لا توجد بيانات متاحة",
            score: "النتيجة",
            progress: "التقدم",
            month: "الشهر",
            category: "الفئة"
        }
    };

    const t = translations[currentLocale] || translations['en'];
    const isRtl = currentLocale === 'ar';

    const pieChartColors = ['#4CAF50', '#F44336', '#9E9E9E'];
    const barChartColors = ['#2196F3', '#3F51B5', '#673AB7', '#009688', '#FF5722'];

    // Prepare data for the response summary pie chart
    const responseSummaryData = [
        { name: t.yes, value: stats.yesAnswers },
        { name: t.no, value: stats.noAnswers },
        { name: t.notApplicable, value: stats.naAnswers }
    ];

    return (
        <>
            <Head title={t.assessmentResults} />

            <div className="results-header">
                <h1>{t.assessmentResults}</h1>
                <p>{registration.name} - {registration.company_name}</p>
            </div>

            {loading ? (
                <div className="loading-container">
                    <div className="loading-spinner"></div>
                    <p>{t.loading}</p>
                </div>
            ) : (
                <div className="results-container">
                    <div className="results-section overview-section">
                        <h2 className="section-title">{t.overview}</h2>
                        <div className="stat-cards">
                            <div className="stat-card">
                                <h3>{t.completionRate}</h3>
                                <p className="stat-value">{stats.completionRate}%</p>
                            </div>
                            <div className="stat-card">
                                <h3>{t.responseSummary}</h3>
                                <p className="stat-value">{stats.totalAnswers} <span className="stat-label">responses</span></p>
                            </div>
                        </div>
                    </div>

                    <div className="charts-row">
                        <div className="chart-card">
                            <h3 className="chart-title">{t.responseSummary}</h3>
                            <div className="chart-container">
                                <ResponsiveContainer width="100%" height={300}>
                                    <PieChart>
                                        <Pie
                                            data={responseSummaryData}
                                            cx="50%"
                                            cy="50%"
                                            outerRadius={80}
                                            dataKey="value"
                                            labelLine={true}
                                            label={({ name, percent }) => `${name}: ${(percent * 100).toFixed(0)}%`}
                                        >
                                            {responseSummaryData.map((entry, index) => (
                                                <Cell key={`cell-${index}`} fill={pieChartColors[index % pieChartColors.length]} />
                                            ))}
                                        </Pie>
                                        <Tooltip />
                                        <Legend />
                                    </PieChart>
                                </ResponsiveContainer>
                            </div>
                        </div>

                        <div className="chart-card">
                            <h3 className="chart-title">{t.categoryPerformance}</h3>
                            <div className="chart-container">
                                <ResponsiveContainer width="100%" height={300}>
                                    <BarChart data={stats.categoryScores}>
                                        <CartesianGrid strokeDasharray="3 3" />
                                        <XAxis dataKey="name" label={{ value: t.category, position: 'insideBottom', offset: -5 }} />
                                        <YAxis label={{ value: t.score, angle: -90, position: 'insideLeft' }} />
                                        <Tooltip />
                                        <Bar dataKey="score" name={t.score}>
                                            {stats.categoryScores.map((entry, index) => (
                                                <Cell key={`cell-${index}`} fill={barChartColors[index % barChartColors.length]} />
                                            ))}
                                        </Bar>
                                    </BarChart>
                                </ResponsiveContainer>
                            </div>
                        </div>
                    </div>

                    <div className="full-width-chart">
                        <h3 className="chart-title">{t.progressOverTime}</h3>
                        <div className="chart-container">
                            <ResponsiveContainer width="100%" height={300}>
                                <LineChart data={stats.progressOverTime}>
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis dataKey="month" label={{ value: t.month, position: 'insideBottom', offset: -5 }} />
                                    <YAxis label={{ value: t.progress, angle: -90, position: 'insideLeft' }} />
                                    <Tooltip />
                                    <Legend />
                                    <Line type="monotone" dataKey="progress" stroke="#8884d8" activeDot={{ r: 8 }} />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            )}

            <style jsx>{`
                /* Core variables (using the same variables from AssessmentPage) */
                :root {
                  --primary: #2b6cb0;
                  --primary-light: #4299e1;
                  --primary-dark: #2c5282;
                  --accent: #f5f5dc;
                  --accent-light: #fffff0;
                  --dark: #1a1a2e;
                  --light: #ffffff;
                  --light-gray: #f8f9fc;
                  --medium-gray: #e2e8f0;
                  --text-gray: #6c757d;
                  --success: #38b2ac;
                  --shadow: 0 5px 15px rgba(0,0,0,0.08);
                  --transition: all 0.3s ease;
                }

                *, *::before, *::after {
                  box-sizing: border-box;
                  margin: 0;
                  padding: 0;
                }

                /* Results Header styles */
                .results-header {
                  margin-bottom: 2rem;
                }

                .results-header h1 {
                  font-size: 2rem;
                  font-weight: 700;
                  color: var(--primary);
                  margin-bottom: 0.5rem;
                }

                .results-header p {
                  color: var(--text-gray);
                  font-size: 1.1rem;
                }

                /* Loading state */
                .loading-container {
                  display: flex;
                  flex-direction: column;
                  align-items: center;
                  justify-content: center;
                  height: 400px;
                  background-color: var(--light);
                  border-radius: 10px;
                  box-shadow: var(--shadow);
                }

                .loading-spinner {
                  width: 50px;
                  height: 50px;
                  border: 5px solid var(--light-gray);
                  border-top: 5px solid var(--primary);
                  border-radius: 50%;
                  animation: spin 1s linear infinite;
                  margin-bottom: 1rem;
                }

                @keyframes spin {
                  0% { transform: rotate(0deg); }
                  100% { transform: rotate(360deg); }
                }

                /* Results container */
                .results-container {
                  display: flex;
                  flex-direction: column;
                  gap: 1.5rem;
                }

                .results-section {
                  background-color: var(--light);
                  border-radius: 10px;
                  box-shadow: var(--shadow);
                  padding: 1.5rem;
                }

                .section-title {
                  font-size: 1.5rem;
                  font-weight: 600;
                  color: var(--primary);
                  margin-bottom: 1.25rem;
                  padding-bottom: 0.75rem;
                  border-bottom: 2px solid var(--light-gray);
                }

                /* Stat cards for overview */
                .stat-cards {
                  display: flex;
                  flex-wrap: wrap;
                  gap: 1rem;
                }

                .stat-card {
                  flex: 1;
                  min-width: 200px;
                  background-color: var(--light);
                  border: 1px solid var(--medium-gray);
                  border-radius: 8px;
                  padding: 1.25rem;
                  transition: var(--transition);
                }

                .stat-card:hover {
                  box-shadow: var(--shadow);
                }

                .stat-card h3 {
                  font-size: 1rem;
                  color: var(--text-gray);
                  margin-bottom: 0.5rem;
                }

                .stat-value {
                  font-size: 2rem;
                  font-weight: 700;
                  color: var(--dark);
                }

                .stat-label {
                  font-size: 1rem;
                  color: var(--text-gray);
                  font-weight: normal;
                }

                /* Charts layout */
                .charts-row {
                  display: flex;
                  flex-wrap: wrap;
                  gap: 1.5rem;
                  margin-bottom: 1.5rem;
                }

                .chart-card {
                  flex: 1;
                  min-width: 300px;
                  background-color: var(--light);
                  border-radius: 10px;
                  box-shadow: var(--shadow);
                  padding: 1.5rem;
                }

                .full-width-chart {
                  width: 100%;
                  background-color: var(--light);
                  border-radius: 10px;
                  box-shadow: var(--shadow);
                  padding: 1.5rem;
                }

                .chart-title {
                  font-size: 1.25rem;
                  font-weight: 600;
                  color: var(--primary);
                  margin-bottom: 1.25rem;
                }

                .chart-container {
                  width: 100%;
                  height: 300px;
                }

                /* Responsive styles */
                @media (max-width: 992px) {
                  .charts-row {
                    flex-direction: column;
                  }

                  .chart-card {
                    width: 100%;
                  }
                }

                @media (max-width: 576px) {
                  .results-header h1 {
                    font-size: 1.5rem;
                  }

                  .results-header p {
                    font-size: 1rem;
                  }

                  .chart-container {
                    height: 250px;
                  }
                }
            `}</style>
        </>
    );
};

export default AssessmentReportDashboard;
