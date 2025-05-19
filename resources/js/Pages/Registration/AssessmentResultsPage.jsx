import React, { useState, useEffect } from 'react';
import { LineChart, BarChart, Bar, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';

const AssessmentReportDashboard = ({ assessmentId, locale = 'ar' }) => {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [assessment, setAssessment] = useState(null);
    const [statistics, setStatistics] = useState({
        totalItems: 0,
        availableItems: 0,
        unavailableItems: 0,
        availableRate: 0,
        unavailableRate: 0,
        completionRate: 0
    });
    const [domains, setDomains] = useState([]);
    const [emailForm, setEmailForm] = useState({
        email: '',
        subject: '',
        message: '',
        sending: false,
        result: null
    });

    const currentLocale = locale; // Use the passed locale prop

    // Translations for the dashboard
    const translations = {
        en: {
            loading: "Loading...",
            error: "Error",
            noData: "No assessment data available",
            assessmentResults: "Assessment Results",
            assessmentDate: "Assessment Date",
            availableItems: "Available Items",
            unavailableItems: "Unavailable Items",
            assessmentCompletion: "Assessment Completion",
            criteriaAvailability: "Criteria Availability",
            yes: "Yes",
            no: "No",
            domainDistribution: "Domain Distribution (Availability)",
            available: "Available",
            unavailable: "Unavailable",
            downloadPdf: "Download PDF Report",
            sendReportEmail: "Send Report via Email",
            emailAddress: "Email Address",
            emailPlaceholder: "Enter recipient's email",
            subject: "Subject (Optional)",
            subjectPlaceholder: "Enter email subject",
            message: "Message (Optional)",
            messagePlaceholder: "Enter your message",
            send: "Send",
            sending: "Sending...",
            emailSuccess: "Report sent successfully!",
            emailError: "Failed to send report.",
            enterEmailError: "Please enter an email address."
        },
        ar: {
            loading: "جاري التحميل...",
            error: "خطأ",
            noData: "لا توجد بيانات للتقييم",
            assessmentResults: "نتائج التقييم",
            assessmentDate: "تاريخ التقييم",
            availableItems: "العناصر المتوفرة",
            unavailableItems: "العناصر غير المتوفرة",
            assessmentCompletion: "اكتمال التقييم",
            criteriaAvailability: "حالة توفر المعايير",
            yes: "نعم",
            no: "لا",
            domainDistribution: "توزيع المجالات (حسب التوفر)",
            available: "متوفر",
            unavailable: "غير متوفر",
            downloadPdf: "تحميل التقرير PDF",
            sendReportEmail: "إرسال التقرير عبر البريد الإلكتروني",
            emailAddress: "عنوان البريد الإلكتروني",
            emailPlaceholder: "أدخل بريد المستلم",
            subject: "الموضوع (اختياري)",
            subjectPlaceholder: "أدخل موضوع الرسالة",
            message: "الرسالة (اختياري)",
            messagePlaceholder: "أدخل رسالتك",
            send: "إرسال",
            sending: "جاري الإرسال...",
            emailSuccess: "تم إرسال التقرير بنجاح!",
            emailError: "فشل إرسال التقرير.",
            enterEmailError: "يرجى إدخال عنوان البريد الإلكتروني."
        }
    };
    const t = translations[currentLocale] || translations.en;


    useEffect(() => {
        if (!assessmentId) return;

        const fetchAssessmentData = async () => {
            try {
                setLoading(true);
                const response = await fetch(`/api/assessments/${assessmentId}/data?locale=${currentLocale}`);

                if (!response.ok) {
                    throw new Error(`${t.error}: ${response.status}`);
                }

                const data = await response.json();
                setAssessment(data.assessment);
                setStatistics(data.statistics);
                setDomains(data.domains);
                setError(null);
            } catch (err) {
                setError(err.message);
                console.error("Failed to fetch assessment data:", err);
            } finally {
                setLoading(false);
            }
        };

        fetchAssessmentData();
    }, [assessmentId, currentLocale, t.error]); // Added currentLocale and t.error to dependencies

    const availabilityData = [
        { name: t.yes, value: statistics.availableItems, color: '#10B981' },
        { name: t.no, value: statistics.unavailableItems, color: '#EF4444' }
    ];

    const getDomainDistributionData = () => {
        if (!domains || !assessment?.items) return [];
        return domains.map(domain => {
            const domainCriteriaIds = domain.categories.flatMap(category =>
                category.criteria.map(criterion => criterion.id)
            );
            const availableCount = assessment.items.filter(
                item => domainCriteriaIds.includes(item.criteria_id) && item.is_available
            ).length;
            const unavailableCount = assessment.items.filter(
                item => domainCriteriaIds.includes(item.criteria_id) && !item.is_available
            ).length;
            return {
                name: currentLocale === 'ar' ? domain.name_ar : domain.name,
                [t.available]: availableCount, // Use translated key for Bar dataKey
                [t.unavailable]: unavailableCount, // Use translated key for Bar dataKey
            };
        });
    };
    const domainData = getDomainDistributionData();


    const downloadReport = () => {
        window.open(`/assessment-reports/export-pdf/${assessmentId}?locale=${currentLocale}`, '_blank');
    };

    const handleEmailFormChange = (e) => {
        const { name, value } = e.target;
        setEmailForm(prev => ({ ...prev, [name]: value, result: null })); // Clear previous result on change
    };

    const sendReportEmail = async (e) => {
        e.preventDefault();
        if (!emailForm.email) {
            setEmailForm(prev => ({ ...prev, result: { success: false, message: t.enterEmailError } }));
            return;
        }
        try {
            setEmailForm(prev => ({ ...prev, sending: true, result: null }));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const headers = { 'Content-Type': 'application/json' };
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }

            const response = await fetch(`/api/assessments/${assessmentId}/send-report`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({
                    email: emailForm.email,
                    subject: emailForm.subject,
                    message: emailForm.message,
                    locale: currentLocale
                })
            });
            const data = await response.json();
            setEmailForm(prev => ({
                ...prev,
                sending: false,
                result: { success: data.success, message: data.message }
            }));
            if (data.success) {
                setEmailForm(prev => ({ ...prev, email: '', subject: '', message: '' }));
            }
        } catch (err) {
            console.error("Error sending report:", err);
            setEmailForm(prev => ({
                ...prev,
                sending: false,
                result: { success: false, message: t.emailError }
            }));
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center min-h-64 text-gray-700 dark:text-gray-300">
                <div className="text-lg font-medium">{t.loading}</div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="flex justify-center items-center min-h-64 text-red-600 dark:text-red-400">
                <div className="text-lg font-medium">{error}</div>
            </div>
        );
    }

    if (!assessment) {
        return (
            <div className="flex justify-center items-center min-h-64 text-gray-700 dark:text-gray-300">
                <div className="text-lg font-medium">{t.noData}</div>
            </div>
        );
    }

    const pageDirectionClass = currentLocale === 'ar' ? 'rtl' : 'ltr';

    return (
        <div className={`p-4 md:p-6 bg-white dark:bg-gray-900 rounded-lg shadow-sm ${pageDirectionClass}`}>
            <div className="text-center mb-8">
                <h1 className="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                    {t.assessmentResults}
                </h1>
                {assessment.name_ar && assessment.name && (
                    <h2 className="text-xl md:text-2xl text-gray-700 dark:text-gray-300 mt-1">
                        {currentLocale === 'ar' ? assessment.name_ar : assessment.name}
                    </h2>
                )}
                <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {t.assessmentDate}: {assessment.date}
                </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                <div className="bg-green-50 dark:bg-green-800/30 p-4 md:p-6 rounded-lg text-center shadow">
                    <h3 className="text-md md:text-lg font-semibold text-green-800 dark:text-green-200">
                        {t.availableItems}
                    </h3>
                    <p className="text-2xl md:text-3xl font-bold text-green-700 dark:text-green-300 my-1">
                        {statistics.availableItems} / {statistics.totalItems}
                    </p>
                    <p className="text-sm text-green-600 dark:text-green-400">{statistics.availableRate?.toFixed(1)}%</p>
                </div>
                <div className="bg-red-50 dark:bg-red-800/30 p-4 md:p-6 rounded-lg text-center shadow">
                    <h3 className="text-md md:text-lg font-semibold text-red-800 dark:text-red-200">
                        {t.unavailableItems}
                    </h3>
                    <p className="text-2xl md:text-3xl font-bold text-red-700 dark:text-red-300 my-1">
                        {statistics.unavailableItems} / {statistics.totalItems}
                    </p>
                    <p className="text-sm text-red-600 dark:text-red-400">{statistics.unavailableRate?.toFixed(1)}%</p>
                </div>
                <div className="bg-blue-50 dark:bg-blue-800/30 p-4 md:p-6 rounded-lg text-center shadow">
                    <h3 className="text-md md:text-lg font-semibold text-blue-800 dark:text-blue-200">
                        {t.assessmentCompletion}
                    </h3>
                    <p className="text-2xl md:text-3xl font-bold text-blue-700 dark:text-blue-300 my-1">
                        {statistics.totalItems} / 39 {/* Consider making '39' dynamic if possible */}
                    </p>
                    <p className="text-sm text-blue-600 dark:text-blue-400">{statistics.completionRate?.toFixed(1)}%</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div className="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-semibold mb-4 text-gray-800 dark:text-white text-center">
                        {t.criteriaAvailability}
                    </h3>
                    <div style={{ height: '300px' }}>
                        <ResponsiveContainer width="100%" height="100%">
                            <PieChart>
                                <Pie data={availabilityData} dataKey="value" nameKey="name" cx="50%" cy="50%" outerRadius={100} label>
                                    {availabilityData.map((entry, index) => (
                                        <Cell key={`cell-${index}`} fill={entry.color} />
                                    ))}
                                </Pie>
                                <Tooltip />
                                <Legend />
                            </PieChart>
                        </ResponsiveContainer>
                    </div>
                </div>
                <div className="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-semibold mb-4 text-gray-800 dark:text-white text-center">
                        {t.domainDistribution}
                    </h3>
                    <div style={{ height: '300px' }}>
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart data={domainData} layout="vertical" margin={{ top: 5, right: 30, left: 20, bottom: 5 }}>
                                <CartesianGrid strokeDasharray="3 3" />
                                <XAxis type="number" />
                                <YAxis type="category" dataKey="name" width={100} tick={{ fontSize: 12 }} />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey={t.available} stackId="a" fill="#10B981" />
                                <Bar dataKey={t.unavailable} stackId="a" fill="#EF4444" />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </div>
            </div>

            <div className="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                    <button
                        onClick={downloadReport}
                        className="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium text-xs uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                    >
                        {t.downloadPdf}
                    </button>
                </div>
            </div>

            <div className="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 className="text-xl font-semibold mb-4 text-gray-800 dark:text-white">{t.sendReportEmail}</h3>
                <form onSubmit={sendReportEmail} className="space-y-4">
                    <div>
                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300">{t.emailAddress}</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value={emailForm.email}
                            onChange={handleEmailFormChange}
                            placeholder={t.emailPlaceholder}
                            className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                        />
                    </div>
                    <div>
                        <label htmlFor="subject" className="block text-sm font-medium text-gray-700 dark:text-gray-300">{t.subject}</label>
                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            value={emailForm.subject}
                            onChange={handleEmailFormChange}
                            placeholder={t.subjectPlaceholder}
                            className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                        />
                    </div>
                    <div>
                        <label htmlFor="message" className="block text-sm font-medium text-gray-700 dark:text-gray-300">{t.message}</label>
                        <textarea
                            name="message"
                            id="message"
                            rows="3"
                            value={emailForm.message}
                            onChange={handleEmailFormChange}
                            placeholder={t.messagePlaceholder}
                            className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                        />
                    </div>
                    <div>
                        <button
                            type="submit"
                            disabled={emailForm.sending}
                            className="w-full md:w-auto px-6 py-2.5 bg-green-600 text-white font-medium text-xs uppercase rounded shadow-md hover:bg-green-700 hover:shadow-lg focus:bg-green-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-green-800 active:shadow-lg transition duration-150 ease-in-out disabled:opacity-50"
                        >
                            {emailForm.sending ? t.sending : t.send}
                        </button>
                    </div>
                    {emailForm.result && (
                        <p className={`mt-2 text-sm ${emailForm.result.success ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`}>
                            {emailForm.result.message}
                        </p>
                    )}
                </form>
            </div>
        </div>
    );
};

export default AssessmentReportDashboard;
