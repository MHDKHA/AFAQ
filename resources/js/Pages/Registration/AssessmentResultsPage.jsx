import toast from 'react-hot-toast';
import axios from 'axios';
import { useState } from 'react';

const AssessmentReportDashboard = ({ assessment, statistics, domains, locale = 'ar' }) => {
    const [sendingEmail, setSendingEmail] = useState(false);
    const [emailForm, setEmailForm] = useState({
        email: '',
        subject: '',
        message: ''
    });

    const handleEmailChange = (e) => {
        const { name, value } = e.target;
        setEmailForm(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const sendReport = async () => {
        if (!emailForm.email) {
            toast.error(locale === 'ar' ? 'يرجى إدخال عنوان البريد الإلكتروني' : 'Please enter an email address');
            return;
        }

        setSendingEmail(true);

        try {
            const response = await axios.post(`/assessment-dashboard/${assessment.id}/send-report`, {
                ...emailForm,
                locale
            });

            toast.success(locale === 'ar' ? 'تم إرسال التقرير بنجاح' : 'Report sent successfully');

            // Reset form
            setEmailForm({
                email: '',
                subject: '',
                message: ''
            });

        } catch (error) {
            console.error('Error sending report:', error);
            toast.error(locale === 'ar' ? 'فشل إرسال التقرير' : 'Failed to send report');
        } finally {
            setSendingEmail(false);
        }
    };

    const printReport = () => {
        window.open(`/assessment/${assessment.id}/print?locale=${locale}`, '_blank');
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 className="text-2xl font-bold mb-6 text-center">
                {locale === 'ar' ? 'لوحة التقييم' : 'Assessment Dashboard'}
            </h1>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {/* Stats Cards - Responsive Design */}
                <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-bold text-center mb-2">
                        {locale === 'ar' ? 'العناصر المتوفرة' : 'Available Items'}
                    </h3>
                    <p className="text-center text-2xl">{statistics.availableItems} / {statistics.totalItems}</p>
                    <p className="text-center text-lg text-green-600">{statistics.availableRate}%</p>
                </div>

                <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-bold text-center mb-2">
                        {locale === 'ar' ? 'العناصر غير المتوفرة' : 'Unavailable Items'}
                    </h3>
                    <p className="text-center text-2xl">{statistics.unavailableItems} / {statistics.totalItems}</p>
                    <p className="text-center text-lg text-red-600">{statistics.unavailableRate}%</p>
                </div>

                <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-bold text-center mb-2">
                        {locale === 'ar' ? 'اكتمال التقييم' : 'Assessment Completion'}
                    </h3>
                    <p className="text-center text-2xl">{statistics.totalItems} / 39</p>
                    <p className="text-center text-lg text-blue-600">{statistics.completionRate}%</p>
                </div>
            </div>

            {/* Actions Row */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-bold mb-4">
                        {locale === 'ar' ? 'طباعة التقرير' : 'Print Report'}
                    </h3>
                    <button
                        onClick={printReport}
                        className="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm"
                    >
                        {locale === 'ar' ? 'طباعة التقرير' : 'Print Report'}
                    </button>
                </div>

                <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 className="text-lg font-bold mb-4">
                        {locale === 'ar' ? 'إرسال التقرير بالبريد الإلكتروني' : 'Email Report'}
                    </h3>

                    <div className="space-y-3">
                        <div>
                            <label className="block text-sm font-medium mb-1">
                                {locale === 'ar' ? 'البريد الإلكتروني' : 'Email'}
                            </label>
                            <input
                                type="email"
                                name="email"
                                value={emailForm.email}
                                onChange={handleEmailChange}
                                className="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-blue-500 focus:ring-blue-500"
                                placeholder={locale === 'ar' ? 'أدخل البريد الإلكتروني' : 'Enter email address'}
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium mb-1">
                                {locale === 'ar' ? 'الموضوع (اختياري)' : 'Subject (Optional)'}
                            </label>
                            <input
                                type="text"
                                name="subject"
                                value={emailForm.subject}
                                onChange={handleEmailChange}
                                className="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-blue-500 focus:ring-blue-500"
                                placeholder={locale === 'ar' ? 'موضوع البريد الإلكتروني' : 'Email subject'}
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium mb-1">
                                {locale === 'ar' ? 'الرسالة (اختياري)' : 'Message (Optional)'}
                            </label>
                            <textarea
                                name="message"
                                value={emailForm.message}
                                onChange={handleEmailChange}
                                rows="3"
                                className="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-blue-500 focus:ring-blue-500"
                                placeholder={locale === 'ar' ? 'نص الرسالة' : 'Message body'}
                            ></textarea>
                        </div>

                        <button
                            onClick={sendReport}
                            disabled={sendingEmail}
                            className="w-full px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-md shadow-sm"
                        >
                            {sendingEmail ?
                                (locale === 'ar' ? 'جاري الإرسال...' : 'Sending...') :
                                (locale === 'ar' ? 'إرسال التقرير' : 'Send Report')
                            }
                        </button>
                    </div>
                </div>
            </div>

            {/* Assessment Results */}
            <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-2 sm:p-4 md:p-6 mb-6">
                <h2 className="text-xl font-semibold mb-4 text-center">
                    {locale === 'ar' ? 'نتائج التقييم' : 'Assessment Results'}
                </h2>

                <div className="overflow-x-auto">
                    {domains.map(domain => (
                        <div key={domain.id} className="mb-6">
                            <h3 className="text-lg font-bold p-2 bg-gray-100 dark:bg-gray-700 rounded-lg mb-4">
                                {locale === 'ar' ? domain.name_ar || domain.name : domain.name}
                            </h3>

                            {domain.categories.map(category => (
                                <div key={category.id} className="mb-4">
                                    <h4 className="text-md font-semibold p-2 bg-gray-50 dark:bg-gray-600 rounded-lg mb-2 border-r-4 border-blue-500">
                                        {locale === 'ar' ? category.name_ar || category.name : category.name}
                                    </h4>

                                    <div className="overflow-x-auto">
                                        <table className="w-full border-collapse min-w-full">
                                            <thead>
                                            <tr className="bg-gray-50 dark:bg-gray-700">
                                                <th className="p-2 md:p-3 border border-gray-200 dark:border-gray-600 text-right font-medium w-12">
                                                    {locale === 'ar' ? 'م' : '#'}
                                                </th>
                                                <th className="p-2 md:p-3 border border-gray-200 dark:border-gray-600 text-right font-medium">
                                                    {locale === 'ar' ? 'السؤال التدقيقي' : 'Audit Question'}
                                                </th>
                                                <th className="p-2 md:p-3 border border-gray-200 dark:border-gray-600 text-center font-medium w-24">
                                                    {locale === 'ar' ? 'حالة التوفر' : 'Status'}
                                                </th>
                                                <th className="p-2 md:p-3 border border-gray-200 dark:border-gray-600 text-right font-medium w-1/4">
                                                    {locale === 'ar' ? 'ملاحظات' : 'Notes'}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {category.criteria.map(criterion => {
                                                const assessmentItem = assessment.items.find(item => item.criteria_id === criterion.id);

                                                return (
                                                    <tr key={criterion.id} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                        <td className="p-2 md:p-3 border border-gray-200 dark:border-gray-600 text-center">
                                                            {criterion.order}
                                                        </td>
                                                        <td className="p-2 md:p-3 border border-gray-200 dark:border-gray-600">
                                                            {locale === 'ar' ? criterion.question_ar || criterion.question : criterion.question}
                                                        </td>
                                                        <td className={`p-2 md:p-3 border border-gray-200 dark:border-gray-600 text-center font-bold ${assessmentItem?.is_available ? 'text-green-600' : 'text-red-600'}`}>
                                                            {assessmentItem?.is_available ?
                                                                (locale === 'ar' ? 'نعم' : 'Yes') :
                                                                (locale === 'ar' ? 'لا' : 'No')
                                                            }
                                                        </td>
                                                        <td className="p-2 md:p-3 border border-gray-200 dark:border-gray-600">
                                                            {assessmentItem?.notes || ''}
                                                        </td>
                                                    </tr>
                                                );
                                            })}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default AssessmentReportDashboard;
