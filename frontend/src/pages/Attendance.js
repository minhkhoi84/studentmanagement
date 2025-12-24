import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import {
  Container,
  Typography,
  Box,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip,
  CircularProgress,
  Alert,
  Card,
  CardContent,
  Grid,
  TextField,
  Select,
  MenuItem,
  FormControl,
  InputLabel,
  IconButton,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Snackbar,
  Tabs,
  Tab,
  Avatar,
  List,
  ListItem,
  ListItemText,
  ListItemAvatar
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Search as SearchIcon,
  FilterList as FilterIcon,
  Assignment as AssignmentIcon,
  Person as PersonIcon,
  School as SchoolIcon,
  CalendarToday as CalendarIcon,
  CheckCircle as PresentIcon,
  Cancel as AbsentIcon,
  Schedule as LateIcon,
  Note as ExcusedIcon,
  Visibility as ViewIcon,
  GroupAdd as BulkAddIcon,
  BarChart as StatsIcon
} from '@mui/icons-material';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';

const Attendance = () => {
  const { isSuperAdmin, hasPermission } = useAuth();
  const [attendances, setAttendances] = useState([]);
  const [students, setStudents] = useState([]);
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [studentFilter, setStudentFilter] = useState('');
  const [courseFilter, setCourseFilter] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const [dateFrom, setDateFrom] = useState(null);
  const [dateTo, setDateTo] = useState(null);
  const [openDialog, setOpenDialog] = useState(false);
  const [openBulkDialog, setOpenBulkDialog] = useState(false);
  const [editingAttendance, setEditingAttendance] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
  const [tabValue, setTabValue] = useState(0);
  const [stats, setStats] = useState(null);
  
  // Stats filter state
  const [statsCourseFilter, setStatsCourseFilter] = useState('');
  const [statsDateFrom, setStatsDateFrom] = useState(null);
  const [statsDateTo, setStatsDateTo] = useState(null);

  // Form state
  const [formData, setFormData] = useState({
    student_id: '',
    course_id: '',
    attendance_date: new Date(),
    status: 'present',
    notes: ''
  });

  // Bulk form state
  const [bulkFormData, setBulkFormData] = useState({
    course_id: '',
    attendance_date: new Date(),
    attendances: []
  });

  useEffect(() => {
    fetchAttendances();
    fetchStudents();
    fetchCourses();
  }, [searchTerm]); // eslint-disable-line react-hooks/exhaustive-deps
  
  useEffect(() => {
    fetchStats();
  }, [statsCourseFilter, statsDateFrom, statsDateTo]); // eslint-disable-line react-hooks/exhaustive-deps

  const fetchAttendances = async () => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (searchTerm) params.append('search', searchTerm);
      if (studentFilter) params.append('student_id', studentFilter);
      if (courseFilter) params.append('course_id', courseFilter);
      if (statusFilter) params.append('status', statusFilter);
      if (dateFrom) params.append('date_from', dateFrom.toISOString().split('T')[0]);
      if (dateTo) params.append('date_to', dateTo.toISOString().split('T')[0]);

      const response = await fetch(`http://127.0.0.1:8000/api/attendances?${params}`);
      if (!response.ok) throw new Error('Failed to fetch attendances');
      const data = await response.json();
      setAttendances(data.data || data || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const fetchStudents = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/students', {
        headers: {
          'Accept': 'application/json',
        }
      });
      if (!response.ok) throw new Error('Failed to fetch students');
      const data = await response.json();
      setStudents(data.data || data || []);
    } catch (err) {
      console.error('Error fetching students:', err);
    }
  };

  const fetchCourses = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/courses', {
        headers: {
          'Accept': 'application/json',
        }
      });
      if (!response.ok) throw new Error('Failed to fetch courses');
      const data = await response.json();
      setCourses(data.data || data || []);
    } catch (err) {
      console.error('Error fetching courses:', err);
    }
  };

  const fetchStats = async () => {
    try {
      const params = new URLSearchParams();
      if (statsCourseFilter) params.append('course_id', statsCourseFilter);
      if (statsDateFrom) params.append('date_from', statsDateFrom.toISOString().split('T')[0]);
      if (statsDateTo) params.append('date_to', statsDateTo.toISOString().split('T')[0]);
      
      const response = await fetch(`http://127.0.0.1:8000/api/attendance-stats?${params}`);
      if (!response.ok) throw new Error('Failed to fetch stats');
      const data = await response.json();
      setStats(data);
    } catch (err) {
      console.error('Error fetching stats:', err);
    }
  };

  const handleOpenDialog = (attendanceData = null) => {
    if (attendanceData) {
      setEditingAttendance(attendanceData);
      setFormData({
        student_id: attendanceData.student_id || '',
        course_id: attendanceData.course_id || '',
        attendance_date: new Date(attendanceData.attendance_date) || new Date(),
        status: attendanceData.status || 'present',
        notes: attendanceData.notes || ''
      });
    } else {
      setEditingAttendance(null);
      setFormData({
        student_id: '',
        course_id: '',
        attendance_date: new Date(),
        status: 'present',
        notes: ''
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingAttendance(null);
    setFormData({
      student_id: '',
      course_id: '',
      attendance_date: new Date(),
      status: 'present',
      notes: ''
    });
  };

  const handleOpenBulkDialog = () => {
    setBulkFormData({
      course_id: '',
      attendance_date: new Date(),
      attendances: []
    });
    setOpenBulkDialog(true);
  };

  const handleCloseBulkDialog = () => {
    setOpenBulkDialog(false);
    setBulkFormData({
      course_id: '',
      attendance_date: new Date(),
      attendances: []
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingAttendance 
        ? `http://127.0.0.1:8000/api/attendances/${editingAttendance.id}`
        : 'http://127.0.0.1:8000/api/attendances';
      
      const method = editingAttendance ? 'PUT' : 'POST';
      
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          ...formData,
          attendance_date: formData.attendance_date.toISOString().split('T')[0]
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        // Hiển thị lỗi validation chi tiết
        let errorMessage = 'Có lỗi xảy ra khi lưu điểm danh';
        if (data.errors) {
          errorMessage = Object.values(data.errors).flat().join(', ');
        } else if (data.message) {
          errorMessage = data.message;
        }
        throw new Error(errorMessage);
      }

      setSnackbar({
        open: true,
        message: editingAttendance ? 'Điểm danh đã được cập nhật thành công!' : 'Điểm danh đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchAttendances();
      fetchStats();
    } catch (err) {
      setSnackbar({
        open: true,
        message: err.message,
        severity: 'error'
      });
    }
  };

  const handleBulkSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('http://127.0.0.1:8000/api/attendances/bulk', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...bulkFormData,
          attendance_date: bulkFormData.attendance_date.toISOString().split('T')[0]
        }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to create bulk attendance');
      }

      const result = await response.json();
      setSnackbar({
        open: true,
        message: result.message || 'Điểm danh hàng loạt đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseBulkDialog();
      fetchAttendances();
      fetchStats();
    } catch (err) {
      setSnackbar({
        open: true,
        message: 'Có lỗi xảy ra: ' + err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (attendanceId, studentName) => {
    if (window.confirm(`Bạn có chắc muốn xóa điểm danh của "${studentName}" không?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/attendances/${attendanceId}`, {
          method: 'DELETE',
        });

        if (!response.ok) throw new Error('Failed to delete attendance');

        setSnackbar({
          open: true,
          message: 'Điểm danh đã được xóa thành công!',
          severity: 'success'
        });

        fetchAttendances();
        fetchStats();
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  const getStatusIcon = (status) => {
    switch (status) {
      case 'present': return <PresentIcon color="success" />;
      case 'absent': return <AbsentIcon color="error" />;
      case 'late': return <LateIcon color="warning" />;
      case 'excused': return <ExcusedIcon color="info" />;
      default: return <PersonIcon />;
    }
  };

  const getStatusText = (status) => {
    switch (status) {
      case 'present': return 'Có mặt';
      case 'absent': return 'Vắng mặt';
      case 'late': return 'Muộn';
      case 'excused': return 'Có phép';
      default: return 'Không xác định';
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'present': return 'success';
      case 'absent': return 'error';
      case 'late': return 'warning';
      case 'excused': return 'info';
      default: return 'default';
    }
  };

  const handleCourseChange = (courseId) => {
    setBulkFormData({ ...bulkFormData, course_id: courseId });
    
    // Load students for this course
    if (courseId) {
      const courseStudents = students.filter(student => 
        student.courses && student.courses.some(course => course.id === courseId)
      );
      setBulkFormData(prev => ({
        ...prev,
        attendances: courseStudents.map(student => ({
          student_id: student.id,
          status: 'present',
          notes: ''
        }))
      }));
    }
  };

  const handleBulkAttendanceChange = (studentId, field, value) => {
    setBulkFormData(prev => ({
      ...prev,
      attendances: prev.attendances.map(att =>
        att.student_id === studentId ? { ...att, [field]: value } : att
      )
    }));
  };

  const getStudentName = (studentId) => {
    const student = students.find(s => s.id === studentId);
    return student ? student.name : 'Không tìm thấy';
  };

  const getCourseName = (courseId) => {
    const course = courses.find(c => c.id === courseId);
    return course ? course.name : 'Không tìm thấy';
  };

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="400px">
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return <Alert severity="error">Error: {error}</Alert>;
  }

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      {/* Header */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Box>
          <Typography variant="h4" component="h1" gutterBottom>
            <AssignmentIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Điểm Danh
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý điểm danh sinh viên theo lớp học và môn học
          </Typography>
        </Box>
        {(isSuperAdmin() || hasPermission('create-attendance')) && (
          <Box display="flex" gap={2}>
            <Button
              variant="outlined"
              startIcon={<BulkAddIcon />}
              onClick={handleOpenBulkDialog}
            >
              Điểm danh hàng loạt
            </Button>
            <Button
              variant="contained"
              color="primary"
              startIcon={<AddIcon />}
              onClick={() => handleOpenDialog()}
            >
              Thêm điểm danh
            </Button>
          </Box>
        )}
      </Box>

      {/* Tabs for List and Stats */}
      <Card sx={{ mb: 3 }}>
        <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
          <Tabs value={tabValue} onChange={(e, newValue) => setTabValue(newValue)}>
            <Tab label="Danh sách điểm danh" icon={<ViewIcon />} iconPosition="start" />
            <Tab label="Thống kê" icon={<StatsIcon />} iconPosition="start" />
          </Tabs>
        </Box>

        {/* Tab 1: Attendance List */}
        {tabValue === 0 && (
          <CardContent>
            {/* Search and Filters */}
            <Box mb={3}>
              <Grid container spacing={2}>
                <Grid item xs={12} md={4}>
                  <TextField
                    fullWidth
                    label="Tìm kiếm"
                    placeholder="Tên sinh viên, môn học..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    InputProps={{
                      startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />
                    }}
                  />
                </Grid>
                <Grid item xs={12} md={8}>
                  <Grid container spacing={2}>
                    <Grid item xs={12} sm={4}>
                      <FormControl fullWidth>
                        <InputLabel>Sinh viên</InputLabel>
                        <Select
                          value={studentFilter}
                          label="Sinh viên"
                          onChange={(e) => setStudentFilter(e.target.value)}
                        >
                          <MenuItem value="">Tất cả</MenuItem>
                          {students.map(student => (
                            <MenuItem key={student.id} value={student.id}>
                              {student.name}
                            </MenuItem>
                          ))}
                        </Select>
                      </FormControl>
                    </Grid>
                    <Grid item xs={12} sm={4}>
                      <FormControl fullWidth>
                        <InputLabel>Môn học</InputLabel>
                        <Select
                          value={courseFilter}
                          label="Môn học"
                          onChange={(e) => setCourseFilter(e.target.value)}
                        >
                          <MenuItem value="">Tất cả</MenuItem>
                          {courses.map(course => (
                            <MenuItem key={course.id} value={course.id}>
                              {course.name}
                            </MenuItem>
                          ))}
                        </Select>
                      </FormControl>
                    </Grid>
                    <Grid item xs={12} sm={4}>
                      <FormControl fullWidth>
                        <InputLabel>Trạng thái</InputLabel>
                        <Select
                          value={statusFilter}
                          label="Trạng thái"
                          onChange={(e) => setStatusFilter(e.target.value)}
                        >
                          <MenuItem value="">Tất cả</MenuItem>
                          <MenuItem value="present">Có mặt</MenuItem>
                          <MenuItem value="absent">Vắng mặt</MenuItem>
                          <MenuItem value="late">Muộn</MenuItem>
                          <MenuItem value="excused">Có phép</MenuItem>
                        </Select>
                      </FormControl>
                    </Grid>
                  </Grid>
                </Grid>
              </Grid>

              <Grid container spacing={2} mt={1}>
                <Grid item xs={12} md={6}>
                  <Box display="flex" gap={2}>
                    <DatePicker
                      label="Từ ngày"
                      value={dateFrom}
                      onChange={(newValue) => setDateFrom(newValue)}
                      slotProps={{ textField: { fullWidth: true } }}
                    />
                    <DatePicker
                      label="Đến ngày"
                      value={dateTo}
                      onChange={(newValue) => setDateTo(newValue)}
                      slotProps={{ textField: { fullWidth: true } }}
                    />
                  </Box>
                </Grid>
                <Grid item xs={12} md={6}>
                  <Box display="flex" justifyContent="flex-end" gap={2}>
                    <Button
                      variant="outlined"
                      startIcon={<FilterIcon />}
                      onClick={fetchAttendances}
                    >
                      Lọc
                    </Button>
                    <Button
                      variant="outlined"
                      onClick={() => {
                        setSearchTerm('');
                        setStudentFilter('');
                        setCourseFilter('');
                        setStatusFilter('');
                        setDateFrom(null);
                        setDateTo(null);
                      }}
                    >
                      Đặt lại
                    </Button>
                  </Box>
                </Grid>
              </Grid>
            </Box>

            {/* Attendance Table */}
            <TableContainer component={Paper} variant="outlined">
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>#</TableCell>
                    <TableCell>Sinh viên</TableCell>
                    <TableCell>Môn học</TableCell>
                    <TableCell>Ngày điểm danh</TableCell>
                    <TableCell>Trạng thái</TableCell>
                    <TableCell>Ghi chú</TableCell>
                    <TableCell align="center">Thao tác</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {attendances.length === 0 ? (
                    <TableRow>
                      <TableCell colSpan={7} align="center" sx={{ py: 4 }}>
                        <Box textAlign="center">
                          <AssignmentIcon sx={{ fontSize: 48, color: 'text.secondary', mb: 2 }} />
                          <Typography variant="h6" color="text.secondary">
                            Không tìm thấy bản ghi điểm danh nào
                          </Typography>
                          <Typography variant="body2" color="text.secondary">
                            Hãy thử thay đổi bộ lọc hoặc thêm bản ghi mới
                          </Typography>
                        </Box>
                      </TableCell>
                    </TableRow>
                  ) : (
                    attendances.map((attendance, index) => (
                      <TableRow key={attendance.id} hover>
                        <TableCell>{index + 1}</TableCell>
                        <TableCell>
                          <Box display="flex" alignItems="center">
                            <Avatar sx={{ mr: 2, bgcolor: 'primary.main' }}>
                              <PersonIcon />
                            </Avatar>
                            <Box>
                              <Typography variant="subtitle2" fontWeight="bold">
                                {getStudentName(attendance.student_id)}
                              </Typography>
                              <Typography variant="caption" color="text.secondary">
                                ID: #{attendance.student_id}
                              </Typography>
                            </Box>
                          </Box>
                        </TableCell>
                        <TableCell>
                          <Box display="flex" alignItems="center">
                            <SchoolIcon sx={{ mr: 2, color: 'secondary.main' }} />
                            <Box>
                              <Typography variant="subtitle2" fontWeight="bold">
                                {getCourseName(attendance.course_id)}
                              </Typography>
                              <Typography variant="caption" color="text.secondary">
                                ID: #{attendance.course_id}
                              </Typography>
                            </Box>
                          </Box>
                        </TableCell>
                        <TableCell>
                          <Box display="flex" alignItems="center" gap={1}>
                            <CalendarIcon sx={{ fontSize: 18, color: 'text.secondary' }} />
                            <Typography variant="body2">
                              {new Date(attendance.attendance_date).toLocaleDateString('vi-VN')}
                            </Typography>
                          </Box>
                        </TableCell>
                        <TableCell>
                          <Chip
                            icon={getStatusIcon(attendance.status)}
                            label={getStatusText(attendance.status)}
                            color={getStatusColor(attendance.status)}
                            size="small"
                          />
                        </TableCell>
                        <TableCell>
                          <Typography variant="body2" color="text.secondary">
                            {attendance.notes || 'Không có ghi chú'}
                          </Typography>
                        </TableCell>
                        <TableCell align="center">
                          {(isSuperAdmin() || hasPermission('edit-attendance')) && (
                            <>
                              <IconButton
                                size="small"
                                color="primary"
                                onClick={() => handleOpenDialog(attendance)}
                              >
                                <EditIcon />
                              </IconButton>
                              <IconButton
                                size="small"
                                color="error"
                                onClick={() => handleDelete(attendance.id, getStudentName(attendance.student_id))}
                              >
                                <DeleteIcon />
                              </IconButton>
                            </>
                          )}
                        </TableCell>
                      </TableRow>
                    ))
                  )}
                </TableBody>
              </Table>
            </TableContainer>
          </CardContent>
        )}

        {/* Tab 2: Stats */}
        {tabValue === 1 && (
          <CardContent>
            <Grid container spacing={3}>
              <Grid item xs={12} md={4}>
                <Card variant="outlined">
                  <CardContent>
                    <Typography variant="h6" gutterBottom>
                      Bộ lọc thống kê
                    </Typography>
                    <FormControl fullWidth margin="normal">
                      <InputLabel>Môn học</InputLabel>
                      <Select
                        value={statsCourseFilter}
                        label="Môn học"
                        onChange={(e) => setStatsCourseFilter(e.target.value)}
                      >
                        <MenuItem value="">Tất cả</MenuItem>
                        {courses.map(course => (
                          <MenuItem key={course.id} value={course.id}>
                            {course.name}
                          </MenuItem>
                        ))}
                      </Select>
                    </FormControl>
                    <Box mt={2}>
                      <DatePicker
                        label="Từ ngày"
                        value={statsDateFrom}
                        onChange={(newValue) => setStatsDateFrom(newValue)}
                        slotProps={{ textField: { fullWidth: true, margin: 'normal' } }}
                      />
                      <DatePicker
                        label="Đến ngày"
                        value={statsDateTo}
                        onChange={(newValue) => setStatsDateTo(newValue)}
                        slotProps={{ textField: { fullWidth: true, margin: 'normal' } }}
                      />
                    </Box>
                    <Box mt={2}>
                      <Button
                        variant="contained"
                        fullWidth
                        startIcon={<StatsIcon />}
                        onClick={fetchStats}
                      >
                        Cập nhật thống kê
                      </Button>
                    </Box>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} md={8}>
                <Card variant="outlined">
                  <CardContent>
                    <Typography variant="h6" gutterBottom>
                      Kết quả thống kê
                    </Typography>
                    {stats ? (
                      <Grid container spacing={2}>
                        <Grid item xs={12} sm={6}>
                          <Card variant="outlined">
                            <CardContent>
                              <Typography variant="subtitle2" color="text.secondary">
                                Tổng số buổi học
                              </Typography>
                              <Typography variant="h4">
                                {stats.total_sessions || 0}
                              </Typography>
                            </CardContent>
                          </Card>
                        </Grid>
                        <Grid item xs={12} sm={6}>
                          <Card variant="outlined">
                            <CardContent>
                              <Typography variant="subtitle2" color="text.secondary">
                                Tỷ lệ chuyên cần trung bình
                              </Typography>
                              <Typography variant="h4">
                                {stats.average_attendance_rate ? `${(stats.average_attendance_rate * 100).toFixed(1)}%` : '0%'}
                              </Typography>
                            </CardContent>
                          </Card>
                        </Grid>
                        {/* Additional stats can be rendered here similarly */}
                      </Grid>
                    ) : (
                      <Typography color="text.secondary">
                        Chưa có dữ liệu thống kê. Hãy chọn bộ lọc và nhấn "Cập nhật thống kê".
                      </Typography>
                    )}
                  </CardContent>
                </Card>
              </Grid>
            </Grid>
          </CardContent>
        )}
      </Card>

      {/* Single Attendance Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="sm" fullWidth>
        <DialogTitle>{editingAttendance ? 'Chỉnh sửa điểm danh' : 'Thêm điểm danh mới'}</DialogTitle>
        <DialogContent>
          <Box component="form" sx={{ mt: 2 }}>
            <Grid container spacing={2}>
              <Grid item xs={12}>
                <FormControl fullWidth>
                  <InputLabel>Sinh viên</InputLabel>
                  <Select
                    value={formData.student_id}
                    label="Sinh viên"
                    onChange={(e) => setFormData({ ...formData, student_id: e.target.value })}
                  >
                    {students.map(student => (
                      <MenuItem key={student.id} value={student.id}>
                        {student.name}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12}>
                <FormControl fullWidth>
                  <InputLabel>Môn học</InputLabel>
                  <Select
                    value={formData.course_id}
                    label="Môn học"
                    onChange={(e) => setFormData({ ...formData, course_id: e.target.value })}
                  >
                    {courses.map(course => (
                      <MenuItem key={course.id} value={course.id}>
                        {course.name}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12}>
                <DatePicker
                  label="Ngày điểm danh"
                  value={formData.attendance_date}
                  onChange={(newValue) => setFormData({ ...formData, attendance_date: newValue })}
                  slotProps={{ textField: { fullWidth: true } }}
                />
              </Grid>
              <Grid item xs={12}>
                <FormControl fullWidth>
                  <InputLabel>Trạng thái</InputLabel>
                  <Select
                    value={formData.status}
                    label="Trạng thái"
                    onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                  >
                    <MenuItem value="present">Có mặt</MenuItem>
                    <MenuItem value="absent">Vắng mặt</MenuItem>
                    <MenuItem value="late">Muộn</MenuItem>
                    <MenuItem value="excused">Có phép</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  multiline
                  rows={2}
                  label="Ghi chú"
                  value={formData.notes}
                  onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                />
              </Grid>
            </Grid>
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleCloseDialog}>Hủy</Button>
          <Button onClick={handleSubmit} variant="contained" color="primary">
            {editingAttendance ? 'Cập nhật' : 'Tạo mới'}
          </Button>
        </DialogActions>
      </Dialog>

      {/* Bulk Attendance Dialog */}
      <Dialog open={openBulkDialog} onClose={handleCloseBulkDialog} maxWidth="md" fullWidth>
        <DialogTitle>Điểm danh hàng loạt</DialogTitle>
        <DialogContent>
          <Box component="form" sx={{ mt: 2 }}>
            <Grid container spacing={2}>
              <Grid item xs={12} md={6}>
                <FormControl fullWidth>
                  <InputLabel>Môn học</InputLabel>
                  <Select
                    value={bulkFormData.course_id}
                    label="Môn học"
                    onChange={(e) => handleCourseChange(e.target.value)}
                  >
                    {courses.map(course => (
                      <MenuItem key={course.id} value={course.id}>
                        {course.name}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} md={6}>
                <DatePicker
                  label="Ngày điểm danh"
                  value={bulkFormData.attendance_date}
                  onChange={(newValue) => setBulkFormData({ ...bulkFormData, attendance_date: newValue })}
                  slotProps={{ textField: { fullWidth: true } }}
                />
              </Grid>
            </Grid>

            <Box mt={3}>
              <Typography variant="subtitle1" gutterBottom>
                Danh sách sinh viên trong lớp/môn học
              </Typography>
              {bulkFormData.attendances.length === 0 ? (
                <Typography color="text.secondary">
                  Vui lòng chọn môn học để tải danh sách sinh viên.
                </Typography>
              ) : (
                <List>
                  {bulkFormData.attendances.map((att) => {
                    const student = students.find(s => s.id === att.student_id);
                    return (
                      <ListItem key={att.student_id} alignItems="flex-start">
                        <ListItemAvatar>
                          <Avatar>
                            <PersonIcon />
                          </Avatar>
                        </ListItemAvatar>
                        <ListItemText
                          primary={student ? student.name : `ID: ${att.student_id}`}
                          secondary={
                            <Box mt={1}>
                              <FormControl size="small" sx={{ minWidth: 150 }}>
                                <InputLabel>Trạng thái</InputLabel>
                                <Select
                                  value={att.status}
                                  label="Trạng thái"
                                  onChange={(e) => handleBulkAttendanceChange(att.student_id, 'status', e.target.value)}
                                >
                                  <MenuItem value="present">Có mặt</MenuItem>
                                  <MenuItem value="absent">Vắng mặt</MenuItem>
                                  <MenuItem value="late">Muộn</MenuItem>
                                  <MenuItem value="excused">Có phép</MenuItem>
                                </Select>
                              </FormControl>
                              <TextField
                                size="small"
                                sx={{ ml: 2, minWidth: 250 }}
                                label="Ghi chú"
                                value={att.notes}
                                onChange={(e) => handleBulkAttendanceChange(att.student_id, 'notes', e.target.value)}
                              />
                            </Box>
                          }
                        />
                      </ListItem>
                    );
                  })}
                </List>
              )}
            </Box>
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleCloseBulkDialog}>Hủy</Button>
          <Button onClick={handleBulkSubmit} variant="contained" color="primary">
            Lưu điểm danh hàng loạt
          </Button>
        </DialogActions>
      </Dialog>

      {/* Notification */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={6000}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
        message={snackbar.message}
      />
    </Container>
  );
};

export default Attendance;
