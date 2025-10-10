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
  Divider,
  List,
  ListItem,
  ListItemText,
  ListItemAvatar,
  Checkbox,
  FormControlLabel,
  FormGroup
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
  }, [searchTerm]);
  
  useEffect(() => {
    fetchStats();
  }, [statsCourseFilter, statsDateFrom, statsDateTo]);

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
        student.courses && student.courses.some(course => course.id == courseId)
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
        att.student_id === studentId 
          ? { ...att, [field]: value }
          : att
      )
    }));
  };

  // Chỉ filter theo student, course và status ở frontend, search đã được xử lý ở backend
  const filteredAttendances = attendances.filter(attendance => {
    const matchesStudent = !studentFilter || attendance.student_id == studentFilter;
    const matchesCourse = !courseFilter || attendance.course_id == courseFilter;
    const matchesStatus = !statusFilter || attendance.status === statusFilter;
    
    return matchesStudent && matchesCourse && matchesStatus;
  });

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
        {/* Header Section */}
        <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
          <Box>
            <Typography variant="h4" component="h1" gutterBottom>
              <AssignmentIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
              Quản Lý Điểm Danh
            </Typography>
            <Typography variant="subtitle1" color="text.secondary">
              Quản lý điểm danh sinh viên theo môn học
            </Typography>
          </Box>
          <Box display="flex" gap={2}>
            {(isSuperAdmin() || hasPermission('create-attendances')) && (
              <>
                <Button
                  variant="outlined"
                  color="secondary"
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
              </>
            )}
          </Box>
        </Box>

        {/* Tabs */}
        <Card sx={{ mb: 3 }}>
          <Tabs value={tabValue} onChange={(e, newValue) => setTabValue(newValue)}>
            <Tab label="Danh sách điểm danh" icon={<AssignmentIcon />} />
            <Tab label="Thống kê" icon={<StatsIcon />} />
          </Tabs>
        </Card>

        {tabValue === 0 && (
          <>
            {/* Search and Filter Section */}
            <Card sx={{ mb: 3 }}>
              <CardContent>
                <Grid container spacing={2} alignItems="center">
                  <Grid item xs={12} md={3}>
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
                  <Grid item xs={12} md={2}>
                    <FormControl fullWidth>
                      <InputLabel>Sinh viên</InputLabel>
                      <Select
                        value={studentFilter}
                        label="Sinh viên"
                        onChange={(e) => setStudentFilter(e.target.value)}
                      >
                        <MenuItem value="">Tất cả sinh viên</MenuItem>
                        {students.map((student) => (
                          <MenuItem key={student.id} value={student.id}>
                            {student.name}
                          </MenuItem>
                        ))}
                      </Select>
                    </FormControl>
                  </Grid>
                  <Grid item xs={12} md={2}>
                    <FormControl fullWidth>
                      <InputLabel>Môn học</InputLabel>
                      <Select
                        value={courseFilter}
                        label="Môn học"
                        onChange={(e) => setCourseFilter(e.target.value)}
                      >
                        <MenuItem value="">Tất cả môn học</MenuItem>
                        {courses.map((course) => (
                          <MenuItem key={course.id} value={course.id}>
                            {course.name}
                          </MenuItem>
                        ))}
                      </Select>
                    </FormControl>
                  </Grid>
                  <Grid item xs={12} md={2}>
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
                  <Grid item xs={12} md={3}>
                    <Box display="flex" gap={1}>
                      <DatePicker
                        label="Từ ngày"
                        value={dateFrom}
                        onChange={setDateFrom}
                        renderInput={(params) => <TextField {...params} size="small" />}
                      />
                      <DatePicker
                        label="Đến ngày"
                        value={dateTo}
                        onChange={setDateTo}
                        renderInput={(params) => <TextField {...params} size="small" />}
                      />
                    </Box>
                  </Grid>
                </Grid>
                <Box mt={2} display="flex" gap={2}>
                  <Button
                    variant="outlined"
                    startIcon={<FilterIcon />}
                    onClick={fetchAttendances}
                  >
                    Áp dụng bộ lọc
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
              </CardContent>
            </Card>

            {/* Attendances List */}
            <Card>
              <CardContent>
                <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
                  <Typography variant="h6">
                    Danh sách điểm danh ({filteredAttendances.length} bản ghi)
                  </Typography>
                </Box>
                
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
                      {filteredAttendances.length === 0 ? (
                        <TableRow>
                          <TableCell colSpan={7} align="center" sx={{ py: 4 }}>
                            <Box textAlign="center">
                              <AssignmentIcon sx={{ fontSize: 48, color: 'text.secondary', mb: 2 }} />
                              <Typography variant="h6" color="text.secondary">
                                Không tìm thấy điểm danh nào
                              </Typography>
                              <Typography variant="body2" color="text.secondary">
                                Hãy thử thay đổi bộ lọc hoặc thêm điểm danh mới
                              </Typography>
                            </Box>
                          </TableCell>
                        </TableRow>
                      ) : (
                        filteredAttendances.map((attendance, index) => (
                          <TableRow key={attendance.id} hover>
                            <TableCell>{index + 1}</TableCell>
                            <TableCell>
                              <Box display="flex" alignItems="center">
                                <Avatar sx={{ mr: 2, bgcolor: 'primary.main' }}>
                                  <PersonIcon />
                                </Avatar>
                                <Box>
                                  <Typography variant="subtitle2" fontWeight="bold">
                                    {attendance.student?.name || 'N/A'}
                                  </Typography>
                                  <Typography variant="caption" color="text.secondary">
                                    {attendance.student?.student_code || 'N/A'}
                                  </Typography>
                                </Box>
                              </Box>
                            </TableCell>
                            <TableCell>
                              <Box display="flex" alignItems="center">
                                <SchoolIcon sx={{ mr: 1, color: 'primary.main' }} />
                                <Box>
                                  <Typography variant="subtitle2">
                                    {attendance.course?.name || 'N/A'}
                                  </Typography>
                                  <Typography variant="caption" color="text.secondary">
                                    {attendance.course?.code || 'N/A'}
                                  </Typography>
                                </Box>
                              </Box>
                            </TableCell>
                            <TableCell>
                              <Box display="flex" alignItems="center">
                                <CalendarIcon sx={{ mr: 1, color: 'text.secondary' }} />
                                <Box>
                                  <Typography variant="body2">
                                    {new Date(attendance.attendance_date).toLocaleDateString('vi-VN')}
                                  </Typography>
                                  <Typography variant="caption" color="text.secondary">
                                    {new Date(attendance.attendance_date).toLocaleTimeString('vi-VN', { 
                                      hour: '2-digit', 
                                      minute: '2-digit' 
                                    })}
                                  </Typography>
                                </Box>
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
                              <Box display="flex" gap={1} justifyContent="center">
                                <IconButton
                                  size="small"
                                  color="info"
                                  onClick={() => handleOpenDialog(attendance)}
                                  title="Xem chi tiết"
                                >
                                  <ViewIcon />
                                </IconButton>
                                {(isSuperAdmin() || hasPermission('edit-attendances')) && (
                                  <IconButton
                                    size="small"
                                    color="primary"
                                    onClick={() => handleOpenDialog(attendance)}
                                    title="Chỉnh sửa"
                                  >
                                    <EditIcon />
                                  </IconButton>
                                )}
                                {(isSuperAdmin() || hasPermission('delete-attendances')) && (
                                  <IconButton
                                    size="small"
                                    color="error"
                                    onClick={() => handleDelete(attendance.id, attendance.student?.name)}
                                    title="Xóa điểm danh"
                                  >
                                    <DeleteIcon />
                                  </IconButton>
                                )}
                              </Box>
                            </TableCell>
                          </TableRow>
                        ))
                      )}
                    </TableBody>
                  </Table>
                </TableContainer>
              </CardContent>
            </Card>
          </>
        )}

        {tabValue === 1 && (
          <>
            {/* Stats Filter Section */}
            <Card sx={{ mb: 3 }}>
              <CardContent>
                <Typography variant="h6" gutterBottom>
                  Bộ lọc thống kê
                </Typography>
                <Grid container spacing={2} alignItems="center">
                  <Grid item xs={12} md={4}>
                    <FormControl fullWidth>
                      <InputLabel>Môn học</InputLabel>
                      <Select
                        value={statsCourseFilter}
                        label="Môn học"
                        onChange={(e) => setStatsCourseFilter(e.target.value)}
                      >
                        <MenuItem value="">Tất cả môn học</MenuItem>
                        {courses.map((course) => (
                          <MenuItem key={course.id} value={course.id}>
                            {course.name}
                          </MenuItem>
                        ))}
                      </Select>
                    </FormControl>
                  </Grid>
                  <Grid item xs={12} md={3}>
                    <DatePicker
                      label="Từ ngày"
                      value={statsDateFrom}
                      onChange={setStatsDateFrom}
                      renderInput={(params) => <TextField {...params} fullWidth />}
                    />
                  </Grid>
                  <Grid item xs={12} md={3}>
                    <DatePicker
                      label="Đến ngày"
                      value={statsDateTo}
                      onChange={setStatsDateTo}
                      renderInput={(params) => <TextField {...params} fullWidth />}
                    />
                  </Grid>
                  <Grid item xs={12} md={2}>
                    <Button
                      fullWidth
                      variant="outlined"
                      onClick={() => {
                        setStatsCourseFilter('');
                        setStatsDateFrom(null);
                        setStatsDateTo(null);
                      }}
                    >
                      Đặt lại
                    </Button>
                  </Grid>
                </Grid>
              </CardContent>
            </Card>

            {/* Stats Cards */}
            {stats && (
              <Grid container spacing={3}>
                <Grid item xs={12} md={3}>
              <Card sx={{ bgcolor: 'success.main', color: 'white' }}>
                <CardContent>
                  <Box display="flex" alignItems="center" justifyContent="space-between">
                    <Box>
                      <Typography variant="h6">Có mặt</Typography>
                      <Typography variant="h4">{stats.present || 0}</Typography>
                    </Box>
                    <PresentIcon sx={{ fontSize: 40 }} />
                  </Box>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12} md={3}>
              <Card sx={{ bgcolor: 'error.main', color: 'white' }}>
                <CardContent>
                  <Box display="flex" alignItems="center" justifyContent="space-between">
                    <Box>
                      <Typography variant="h6">Vắng mặt</Typography>
                      <Typography variant="h4">{stats.absent || 0}</Typography>
                    </Box>
                    <AbsentIcon sx={{ fontSize: 40 }} />
                  </Box>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12} md={3}>
              <Card sx={{ bgcolor: 'warning.main', color: 'white' }}>
                <CardContent>
                  <Box display="flex" alignItems="center" justifyContent="space-between">
                    <Box>
                      <Typography variant="h6">Muộn</Typography>
                      <Typography variant="h4">{stats.late || 0}</Typography>
                    </Box>
                    <LateIcon sx={{ fontSize: 40 }} />
                  </Box>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12} md={3}>
              <Card sx={{ bgcolor: 'info.main', color: 'white' }}>
                <CardContent>
                  <Box display="flex" alignItems="center" justifyContent="space-between">
                    <Box>
                      <Typography variant="h6">Có phép</Typography>
                      <Typography variant="h4">{stats.excused || 0}</Typography>
                    </Box>
                    <ExcusedIcon sx={{ fontSize: 40 }} />
                  </Box>
                </CardContent>
              </Card>
            </Grid>
            <Grid item xs={12}>
              <Card>
                <CardContent>
                  <Typography variant="h6" gutterBottom>
                    Tỷ lệ điểm danh: {stats.attendance_rate || 0}%
                  </Typography>
                  <Box 
                    sx={{ 
                      width: '100%', 
                      height: 20, 
                      bgcolor: 'grey.200', 
                      borderRadius: 1,
                      overflow: 'hidden'
                    }}
                  >
                    <Box 
                      sx={{ 
                        width: `${stats.attendance_rate || 0}%`, 
                        height: '100%', 
                        bgcolor: 'success.main',
                        transition: 'width 0.3s ease'
                      }}
                    />
                  </Box>
                </CardContent>
              </Card>
            </Grid>
          </Grid>
            )}
          </>
        )}

        {/* Add/Edit Dialog */}
        <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="sm" fullWidth>
          <DialogTitle>
            {editingAttendance ? 'Chỉnh sửa điểm danh' : 'Thêm điểm danh mới'}
          </DialogTitle>
          <form onSubmit={handleSubmit}>
            <DialogContent>
              <Grid container spacing={2} sx={{ mt: 1 }}>
                <Grid item xs={12} sm={6}>
                  <FormControl fullWidth>
                    <InputLabel>Sinh viên</InputLabel>
                    <Select
                      value={formData.student_id}
                      label="Sinh viên"
                      onChange={(e) => setFormData({ ...formData, student_id: e.target.value })}
                      required
                    >
                      {students.map((student) => (
                        <MenuItem key={student.id} value={student.id}>
                          {student.name} ({student.student_code})
                        </MenuItem>
                      ))}
                    </Select>
                  </FormControl>
                </Grid>
                <Grid item xs={12} sm={6}>
                  <FormControl fullWidth>
                    <InputLabel>Môn học</InputLabel>
                    <Select
                      value={formData.course_id}
                      label="Môn học"
                      onChange={(e) => setFormData({ ...formData, course_id: e.target.value })}
                      required
                    >
                      {courses.map((course) => (
                        <MenuItem key={course.id} value={course.id}>
                          {course.name} ({course.code})
                        </MenuItem>
                      ))}
                    </Select>
                  </FormControl>
                </Grid>
                <Grid item xs={12} sm={6}>
                  <DatePicker
                    label="Ngày điểm danh"
                    value={formData.attendance_date}
                    onChange={(date) => setFormData({ ...formData, attendance_date: date })}
                    renderInput={(params) => <TextField {...params} fullWidth required />}
                  />
                </Grid>
                <Grid item xs={12} sm={6}>
                  <FormControl fullWidth>
                    <InputLabel>Trạng thái</InputLabel>
                    <Select
                      value={formData.status}
                      label="Trạng thái"
                      onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                      required
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
                    label="Ghi chú"
                    multiline
                    rows={3}
                    value={formData.notes}
                    onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                    placeholder="Nhập ghi chú (tùy chọn)"
                  />
                </Grid>
              </Grid>
            </DialogContent>
            <DialogActions>
              <Button onClick={handleCloseDialog}>Hủy</Button>
              <Button type="submit" variant="contained">
                {editingAttendance ? 'Cập nhật' : 'Tạo điểm danh'}
              </Button>
            </DialogActions>
          </form>
        </Dialog>

        {/* Bulk Attendance Dialog */}
        <Dialog open={openBulkDialog} onClose={handleCloseBulkDialog} maxWidth="md" fullWidth>
          <DialogTitle>
            Điểm danh hàng loạt
          </DialogTitle>
          <form onSubmit={handleBulkSubmit}>
            <DialogContent>
              <Grid container spacing={2} sx={{ mt: 1 }}>
                <Grid item xs={12} sm={6}>
                  <FormControl fullWidth>
                    <InputLabel>Môn học</InputLabel>
                    <Select
                      value={bulkFormData.course_id}
                      label="Môn học"
                      onChange={(e) => handleCourseChange(e.target.value)}
                      required
                    >
                      {courses.map((course) => (
                        <MenuItem key={course.id} value={course.id}>
                          {course.name} ({course.code})
                        </MenuItem>
                      ))}
                    </Select>
                  </FormControl>
                </Grid>
                <Grid item xs={12} sm={6}>
                  <DatePicker
                    label="Ngày điểm danh"
                    value={bulkFormData.attendance_date}
                    onChange={(date) => setBulkFormData({ ...bulkFormData, attendance_date: date })}
                    renderInput={(params) => <TextField {...params} fullWidth required />}
                  />
                </Grid>
                <Grid item xs={12}>
                  <Divider sx={{ my: 2 }} />
                  <Typography variant="h6" gutterBottom>
                    Danh sách sinh viên
                  </Typography>
                  <List>
                    {bulkFormData.attendances.map((attendance, index) => {
                      const student = students.find(s => s.id === attendance.student_id);
                      return (
                        <ListItem key={attendance.student_id}>
                          <ListItemAvatar>
                            <Avatar>
                              <PersonIcon />
                            </Avatar>
                          </ListItemAvatar>
                          <ListItemText
                            primary={student?.name}
                            secondary={student?.student_code}
                          />
                          <FormControl size="small" sx={{ minWidth: 120, mr: 2 }}>
                            <InputLabel>Trạng thái</InputLabel>
                            <Select
                              value={attendance.status}
                              label="Trạng thái"
                              onChange={(e) => handleBulkAttendanceChange(attendance.student_id, 'status', e.target.value)}
                            >
                              <MenuItem value="present">Có mặt</MenuItem>
                              <MenuItem value="absent">Vắng mặt</MenuItem>
                              <MenuItem value="late">Muộn</MenuItem>
                              <MenuItem value="excused">Có phép</MenuItem>
                            </Select>
                          </FormControl>
                          <TextField
                            size="small"
                            label="Ghi chú"
                            value={attendance.notes}
                            onChange={(e) => handleBulkAttendanceChange(attendance.student_id, 'notes', e.target.value)}
                            sx={{ minWidth: 150 }}
                          />
                        </ListItem>
                      );
                    })}
                  </List>
                </Grid>
              </Grid>
            </DialogContent>
            <DialogActions>
              <Button onClick={handleCloseBulkDialog}>Hủy</Button>
              <Button type="submit" variant="contained">
                Tạo điểm danh hàng loạt
              </Button>
            </DialogActions>
          </form>
        </Dialog>

        {/* Snackbar for notifications */}
        <Snackbar
          open={snackbar.open}
          autoHideDuration={6000}
          onClose={() => setSnackbar({ ...snackbar, open: false })}
        >
          <Alert 
            onClose={() => setSnackbar({ ...snackbar, open: false })} 
            severity={snackbar.severity}
          >
            {snackbar.message}
          </Alert>
        </Snackbar>
      </Container>
  );
};

export default Attendance;
