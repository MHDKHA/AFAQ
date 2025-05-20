import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer,
    PieChart, Pie, Cell, RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis } from 'recharts';
import axios from 'axios';
import { toast } from 'react-toastify';

const COLORS = ['#10B981', '#EF4444', '#6B7280', '#3B82F6', '#F59E0B', '#8B5CF6'];

const AssessmentDashboard = ({ assessment, domains, statistics, domainStats, locale, canGenerateReport }) => {
    const [activeTab, setActiveTab] = useState('overview');
    const [emailFormVisible, setEmailFormVisible] = useState(false);
    const [emailData, setEmailData] = useState({
        email: '',
        subject: '',
        message: ''
    });
    const [isSending, setIsSending] = useState(false);

    // Format domain stats for charts
    const domainChartData = Object.entries(domainStats).map(([name, stats]) => ({
        name,
        available: stats.available,
        unavailable: stats.unavailable,
        availableRate: stats.availableRate
    }));

    // Pie chart data
    const pieChartData = [
        { name: locale === 'ar' ? 'متوفر' : 'Available', value: statistics.availableItems },
        { name: locale === 'ar' ? 'غير متوفر' : 'Unavailable', value: statistics.unavailableItems }
    ];

    // Format radar data - showing completion percentage by domain
    const radarData = Object.entries(domainStats).map(([name, stats]) => ({
        subject: name,
        A: stats.availableRate,
        fullMark: 100
    }));

    // Handle send report form submission
    const handleSendReport = async (e) => {
        e.preventDefault();
        setIsSending(true);

        try {
            const response = await axios.post(route('assessment-dashboard.send-report', assessment.id), {
                ...emailData,
                locale
            });

            if (response.data.success) {
                setEmailFormVisible(false);
                toast.success(response.data.message);
                setEmailData({
                    email: '',
                    subject: '',
                    message: ''
                });
            }
        } catch (error) {
            toast.error(error.response?.data?.message || 'Failed to send report');
        } finally {
            setIsSending(false);
        }
    };

    return (
        <MainLayout>
            <Head title={`Dashboard - ${assessment.name}`} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8">
                        <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h1 className="text-2xl font-bold">{assessment.name}</h1>
                                <p className="text-gray-500">
                                    {assessment.tool ? assessment.tool.name : ''} | {new Date(assessment.date).toLocaleDateString()}
                                </p>
                            </div>

                            <div className="mt-4 md:mt-0 space-x-3">
                                <Link
                                    href={route('tools.show', assessment.tool?.slug)}
                                    className="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Continue Assessment
                                </Link>

                                {canGenerateReport && (
                                    <button
                                        onClick={() => setEmailFormVisible(true)}
                                        className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                                    >
                                        Generate Report
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Stats cards */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-green-100 rounded-md p-3">
                                        <svg className="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500">
                                                {locale === 'ar' ? 'العناصر المتوفرة' : 'Available Items'}
                                            </dt>
                                            <dd className="flex items-baseline">
                                                <div className="text-2xl font-semibold text-gray-900">
                                                    {statistics.availableItems} / {statistics.totalItems}
                                                </div>
                                                <div className="ml-2 text-sm font-semibold text-green-600">
                                                    {statistics.availableRate}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-red-100 rounded-md p-3">
                                        <svg className="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500">
                                                {locale === 'ar' ? 'العناصر غير المتوفرة' : 'Unavailable Items'}
                                            </dt>
                                            <dd className="flex items-baseline">
                                                <div className="text-2xl font-semibold text-gray-900">
                                                    {statistics.unavailableItems} / {statistics.totalItems}
                                                </div>
                                                <div className="ml-2 text-sm font-semibold text-red-600">
                                                    {statistics.unavailableRate}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                        <svg className="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500">
                                                {locale === 'ar' ? 'اكتمال التقييم' : 'Assessment Completion'}
                                            </dt>
                                            <dd className="flex items-baseline">
                                                <div className="text-2xl font-semibold text-gray-900">
                                                    {statistics.totalItems} / {statistics.totalExpectedItems}
                                                </div>
                                                <div className="ml-2 text-sm font-semibold text-blue-600">
                                                    {statistics.completionRate}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Tabs */}
                    <div className="mb-6 border-b border-gray-200">
                        <nav className="-mb-px flex space-x-8">
                            <button
                                onClick={() => setActiveTab('overview')}
                                className={`${
                                    activeTab === 'overview'
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                } whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm`}
                            >
                                {locale === 'ar' ? 'نظرة عامة' : 'Overview'}
                            </button>

                            <button
                                onClick={() => setActiveTab('domains')}
                                className={`${
                                    activeTab === 'domains'
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                } whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm`}
                            >
                                {locale === 'ar' ? 'المجالات' : 'Domains'}
                            </button>

                            <button
                                onClick={() => setActiveTab('results')}
                                className={`${
                                    activeTab === 'results'
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                } whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm`}
                            >
                                {locale === 'ar' ? 'النتائج التفصيلية' : 'Detailed Results'}
                            </button>
                        </nav>
                    </div>

                    {/* Tab content */}
                    <div className="bg-white shadow rounded-lg p-6">
                        {activeTab === 'overview' && (
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="h-80">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">
                                        {locale === 'ar' ? 'حالة توفر المعايير' : 'Criteria Availability'}
                                    </h3>
                                    <ResponsiveContainer width="100%" height="100%">
                                        <PieChart>
                                            <Pie
                                                data={pieChartData}
                                                cx="50%"
                                                cy="50%"
                                                labelLine={false}
                                                outerRadius={80}
                                                fill="#8884d8"
                                                dataKey="value"
                                                label={({ name, percent }) => `${name}: ${(percent * 100).toFixed(0)}%`}
                                            >
                                                {pieChartData.map((entry, index) => (
                                                    <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                                                ))}
                                            </Pie>
                                            <Tooltip />
                                            <Legend />
                                        </PieChart>
                                    </ResponsiveContainer>
                                </div>

                                <div className="h-80">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">
                                        {locale === 'ar' ? 'مستوى تقييم المعايير' : 'Assessment Level by Domain'}
                                    </h3>
                                    <ResponsiveContainer width="100%" height="100%">
                                        <RadarChart outerRadius={90} data={radarData}>
                                            <PolarGrid />
                                            <PolarAngleAxis dataKey="subject" />
                                            <PolarRadiusAxis angle={30} domain={[0, 100]} />
                                            <Radar name={locale === 'ar' ? 'نسبة التوفر' : 'Availability Rate'} dataKey="A" stroke="#8884d8" fill="#8884d8" fillOpacity={0.6} />
                                            <Legend />
                                            <Tooltip />
                                        </RadarChart>
                                    </ResponsiveContainer>
                                </div>
                            </div>
                        )}

                        {activeTab === 'domains' && (
                            <div>
                                <h3 className="text-lg font-medium text-gray-900 mb-6">
                                    {locale === 'ar' ? 'توزيع المعايير حسب المجالات' : 'Criteria Distribution by Domain'}
                                </h3>
                                <div className="h-96">
                                    <ResponsiveContainer width="100%" height="100%">
                                        <BarChart
                                            data={domainChartData}
                                            margin={{ top: 5, right: 30, left: 20, bottom: 5 }}
                                        >
                                            <CartesianGrid strokeDasharray="3 3" />
                                            <XAxis dataKey="name" />
                                            <YAxis />
                                            <Tooltip />
                                            <Legend />
                                            <Bar dataKey="available" name={locale === 'ar' ? 'متوفر' : 'Available'} fill="#10B981" />
                                            <Bar dataKey="unavailable" name={locale === 'ar' ? 'غير متوفر' : 'Unavailable'} fill="#EF4444" />
                                        </BarChart>
                                    </ResponsiveContainer>
                                </div>

                                <div className="mt-8">
                                    <h4 className="font-medium text-gray-900 mb-4">
                                        {locale === 'ar' ? 'تفاصيل المجالات' : 'Domain Details'}
                                    </h4>
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full divide-y divide-gray-200">
                                            <thead className="bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {locale === 'ar' ? 'المجال' : 'Domain'}
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {locale === 'ar' ? 'متوفر' : 'Available'}
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {locale === 'ar' ? 'غير متوفر' : 'Unavailable'}
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {locale === 'ar' ? 'الإجمالي' : 'Total'}
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {locale === 'ar' ? 'نسبة التوفر' : 'Availability Rate'}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody className="bg-white divide-y divide-gray-200">
                                            {Object.entries(domainStats).map(([name, stats], index) => (
                                                <tr key={index}>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {name}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {stats.available}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {stats.unavailable}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {stats.total}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {stats.availableRate}%
                                                    </td>
                                                </tr>
                                            ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        )}

                        {activeTab === 'results' && (
                            <div>
                                <h3 className="text-lg font-medium text-gray-900 mb-6">
                                    {locale === 'ar' ? 'النتائج التفصيلية للتقييم' : 'Detailed Assessment Results'}
                                </h3>

                                {domains.map(domain => (
                                    <div key={domain.id} className="mb-8">
                                        <h4 className="font-medium text-blue-600 mb-4 pb-2 border-b">
                                            {locale === 'ar' && domain.name_ar ? domain.name_ar : domain.name}
                                        </h4>

                                        {domain.categories.map(category => (
                                            <div key={category.id} className="mb-6">
                                                <h5 className="font-medium text-gray-700 mb-3">
                                                    {locale === 'ar' && category.name_ar ? category.name_ar : category.name}
                                                </h5>

                                                <div className="overflow-x-auto border rounded-lg">
                                                    <table className="min-w-full divide-y divide-gray-200">
                                                        <thead className="bg-gray-50">
                                                        <tr>
                                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                {locale === 'ar' ? 'السؤال التدقيقي' : 'Audit Question'}
                                                            </th>
                                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                {locale === 'ar' ? 'الحالة' : 'Status'}
                                                            </th>
                                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                {locale === 'ar' ? 'ملاحظات' : 'Notes'}
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody className="bg-white divide-y divide-gray-200">
                                                        {category.criteria.map(criterion => {
                                                            const item = assessment.items.find(item => item.criteria_id === criterion.id);
                                                            return (
                                                                <tr key={criterion.id}>
                                                                    <td className="px-6 py-4 text-sm text-gray-900">
                                                                        {locale === 'ar' && criterion.question_ar ? criterion.question_ar : criterion.question}
                                                                    </td>
                                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                                        {item ? (
                                                                            <span className={`px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                                                item.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                                                            }`}>
                                          {item.is_available
                                              ? (locale === 'ar' ? 'متوفر' : 'Available')
                                              : (locale === 'ar' ? 'غير متوفر' : 'Unavailable')}
                                        </span>
                                                                        ) : (
                                                                            <span className="text-gray-400 text-xs">
                                          {locale === 'ar' ? 'غير مقيم' : 'Not assessed'}
                                        </span>
                                                                        )}
                                                                    </td>
                                                                    <td className="px-6 py-4 text-sm text-gray-500">
                                                                        {item?.notes || '-'}
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
                        )}
                    </div>
                </div>
            </div>

            {/* Email report modal */}
            {emailFormVisible && (
                <div className="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onClick={() => setEmailFormVisible(false)}></div>

                    <div className="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 className="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {locale === 'ar' ? 'إرسال التقرير عبر البريد الإلكتروني' : 'Send Report via Email'}
                            </h3>

                            <div className="mt-4">
                                <div className="mb-4">
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                        {locale === 'ar' ? 'البريد الإلكتروني' : 'Email Address'}
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        value={emailData.email}
                                        onChange={(e) => setEmailData({...emailData, email: e.target.value})}
                                        className="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        required
                                    />
                                </div>

                                <div className="mb-4">
                                    <label htmlFor="subject" className="block text-sm font-medium text-gray-700">
                                        {locale === 'ar' ? 'الموضوع' : 'Subject'}
                                    </label>
                                    <input
                                        type="text"
                                        id="subject"
                                        value={emailData.subject}
                                        onChange={(e) => setEmailData({...emailData, subject: e.target.value})}
                                        className="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder={locale === 'ar'
                                            ? `تقرير التقييم: ${assessment.name}`
                                            : `Assessment Report: ${assessment.name}`}
                                    />
                                </div>

                                <div className="mb-4">
                                    <label htmlFor="message" className="block text-sm font-medium text-gray-700">
                                        {locale === 'ar' ? 'الرسالة' : 'Message'}
                                    </label>
                                    <textarea
                                        id="message"
                                        value={emailData.message}
                                        onChange={(e) => setEmailData({...emailData, message: e.target.value})}
                                        rows="4"
                                        className="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div className="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                type="button"
                                onClick={handleSendReport}
                                disabled={isSending}
                                className="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {isSending
                                    ? (locale === 'ar' ? 'جاري الإرسال...' : 'Sending...')
                                    : (locale === 'ar' ? 'إرسال' : 'Send')}
                            </button>
                            <button
                                type="button"
                                onClick={() => setEmailFormVisible(false)}
                                className="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {locale === 'ar' ? 'إلغاء' : 'Cancel'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </MainLayout>
    );
};

export default AssessmentDashboard;
